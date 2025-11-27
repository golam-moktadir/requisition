<?php

namespace App\Services;

use App\Helpers\ShortEncryptor;
use App\Models\Permission;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\{Auth, Log, DB};
use Throwable;

/**
 * Service layer for managing employee permissions.
 *
 * Responsibilities:
 * - Normalizing permission data between form and DB.
 * - Syncing CRUD across primary and mirror DBs (primary authoritative).
 * - Query helpers for list/show/edit views.
 * - Diff logic (create/update/delete).
 * - Session hydration & stale refresh.
 */

class PermissionService
{
    protected ShortEncryptor $encryptor;

    protected string $table = 'permission';
    protected string $mainConn = 'greenline_main'; // primary DB (authoritative)
    protected string $mirrorConn = 'paribahan';    // mirror DB

    /**
     * Constructor
     *
     * @param ShortEncryptor $encryptor ID encryption helper
     */
    public function __construct(ShortEncryptor $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    // =========================================================
    // =============== Normalization & Utilities ===============
    // =========================================================

    /**
     * Return every page_id used by menus + their submenus.
     * Example: [1, 6, 60, 7, 64, 91, ...]
     */
    public function getAllMenuPageIds(): array
    {
        $menus = collect(config('menus'));

        return $menus
            ->map(static function ($menu, $pageId) {
                $sub = collect($menu['submenus'] ?? [])->pluck('page_id')->filter()->all();
                return array_merge([$pageId], $sub);
            })
            ->flatten()
            ->filter(static fn($id) => is_numeric($id))
            ->map(static fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Normalize a single page's permission payload (form-style keys) to DB keys (0/1).
     * Input  : ['view' => 1, 'insert' => 0, 'update' => 1, 'delete' => 0] (any truthy/falsy)
     * Output : ['permission_view'=>1, 'permission_insert'=>0, 'permission_update'=>1, 'permission_delete'=>0]
     */
    protected function normalizePermissionData(array $perms): array
    {
        $to01 = static fn($v) => (int) !empty($v);

        return [
            'permission_view' => $to01($perms['view'] ?? 0),
            'permission_insert' => $to01($perms['insert'] ?? 0),
            'permission_update' => $to01($perms['update'] ?? 0),
            'permission_delete' => $to01($perms['delete'] ?? 0),
        ];
    }

    /**
     * Sanitize arbitrary flags into canonical DB column names (strict 0/1).
     */
    protected function sanitizeFlags(array $flags): array
    {
        $aliases = [
            'view' => 'permission_view',
            'insert' => 'permission_insert',
            'update' => 'permission_update',
            'delete' => 'permission_delete',
        ];

        foreach ($aliases as $from => $to) {
            if (array_key_exists($from, $flags) && !array_key_exists($to, $flags)) {
                $flags[$to] = $flags[$from];
            }
        }

        $defaults = [
            'permission_view' => 0,
            'permission_insert' => 0,
            'permission_update' => 0,
            'permission_delete' => 0,
        ];

        // Keep only expected keys, coercing to 0/1
        return array_map(
            static fn($v) => (int) ((bool) $v),
            array_intersect_key($flags, $defaults) + $defaults
        );
    }

    /**
     * Compare existing vs new → decide which to create, update, or delete.
     *
     * @param array $existing  [pageId => ['permission_view'=>0/1,...]]
     * @param array $new       [pageId => ['view'=>1,...]]
     * @return array{toCreate: array<int,array>, toUpdate: array<int,array>, deletePageIds: int[]}
     */
    protected function diffPermissions(array $existing, array $new): array
    {
        // Normalize new permissions to DB-key shape once
        $normalizedNew = [];
        foreach ($new as $pageId => $perms) {
            $normalizedNew[(int) $pageId] = $this->normalizePermissionData($perms);
        }

        $toCreate = [];
        $toUpdate = [];

        foreach ($normalizedNew as $pageId => $normPerms) {
            if (isset($existing[$pageId])) {
                if ($existing[$pageId] !== $normPerms) {
                    $toUpdate[$pageId] = $normPerms;
                }
            } else {
                $toCreate[$pageId] = $normPerms;
            }
        }

        $deletePageIds = array_values(array_diff(array_keys($existing), array_keys($normalizedNew)));

        return compact('toCreate', 'toUpdate', 'deletePageIds');
    }

    /**
     * Fetch existing rows for an employee (normalized).
     *
     * @return array<int, array{permission_view:int,permission_insert:int,permission_update:int,permission_delete:int}>
     */
    protected function getExistingPermissions(string $connection, int $employeeId): array
    {
        return DB::connection($connection)
            ->table($this->table)
            ->select('page_id', 'permission_view', 'permission_insert', 'permission_update', 'permission_delete')
            ->where('employee_id', $employeeId)
            ->get()
            ->mapWithKeys(static fn($r) => [
                (int) $r->page_id => [
                    'permission_view' => (int) $r->permission_view,
                    'permission_insert' => (int) $r->permission_insert,
                    'permission_update' => (int) $r->permission_update,
                    'permission_delete' => (int) $r->permission_delete,
                ]
            ])
            ->toArray();
    }

    // =========================================================
    // ======================= Listing =========================
    // =========================================================
    public function getPermissionList(string $search = '', int $perPage = 20, int $page = 1): LengthAwarePaginator
    {
        $perPage = $perPage ?: 20;

        // Base query with direct aggregation
        $query = DB::table('permission as p')
            ->join('member as m', 'm.member_id', '=', 'p.employee_id')
            ->leftJoin('employee as e', 'e.employee_id', '=', 'm.member_id')
            ->select(
                'm.member_id',
                'm.member_login',
                DB::raw('COALESCE(e.employee_name, m.member_login) AS employee_name'),
                DB::raw('COUNT(p.page_id) AS permission_count')
            )
            ->groupBy('m.member_id', 'm.member_login', 'e.employee_name');

        // Search filter (optimize with collation or indexes)
        if ($search !== '') {
            $lower = mb_strtolower($search, 'UTF-8');
            $query->where(function ($qq) use ($lower) {
                $qq->whereRaw('LOWER(e.employee_name) LIKE ?', ["%{$lower}%"])
                    ->orWhereRaw('LOWER(m.member_login) LIKE ?', ["%{$lower}%"]);
            });
        }

        return $query
            ->orderBy('employee_name')
            ->paginate($perPage, ['*'], 'page', $page)
            ->withQueryString();
    }

    // =========================================================
    // ====================== Data Prep ========================
    // =========================================================

    /**
     * Fetch transport options from current (default) connection.
     */
    public function getTransportOptions(): Collection
    {
        return DB::table('transport')
            ->select('transport_id', 'transport_name', 'transport_organization_name', 'transport_short_name')
            ->orderBy('transport_name')
            ->get();
    }

    /**
     * Fetch members without existing permissions.
     *
     * @return Collection
     */
    public function getMembersWithoutPermissions(): Collection
    {
        $withPermissions = DB::table('permission')
            ->distinct()
            ->pluck('employee_id');

        return DB::table('member')
            ->whereNotIn('member_id', $withPermissions)
            ->get();
    }

    /**
     * Prepare permission data from validated input.
     * Input arrays: view_ids, insert_ids, update_ids, delete_ids
     * Output: [pageId => ['view'=>0/1,'insert'=>0/1,'update'=>0/1,'delete'=>0/1]]
     */
    public function preparePermissionData(array $validated): array
    {
        $normalize = static function (?array $ids): array {
            return collect($ids ?? [])
                ->map(fn($id) => (int) $id)
                ->filter(fn($id) => $id > 0) // remove 0 or invalid
                ->unique()
                ->values()
                ->all();
        };

        $view = $normalize($validated['view_ids'] ?? []);
        $insert = $normalize($validated['insert_ids'] ?? []);
        $update = $normalize($validated['update_ids'] ?? []);
        $delete = $normalize($validated['delete_ids'] ?? []);

        $allPageIds = collect([$view, $insert, $update, $delete])
            ->flatten()
            ->unique()
            ->values()
            ->all();

        $data = [];

        // New permission entries for each page
        foreach ($allPageIds as $pageId) {
            $data[$pageId] = [
                'view' => in_array($pageId, $view, true) ? 1 : 0,
                'insert' => in_array($pageId, $insert, true) ? 1 : 0,
                'update' => in_array($pageId, $update, true) ? 1 : 0,
                'delete' => in_array($pageId, $delete, true) ? 1 : 0,
            ];
        }

        return $data;
    }

    /**
     * Features (pages) for create view, joined with "new permission" titles.
     */
    public function getFeaturesForCreate(): Collection
    {
        // Pull all pages
        $pages = DB::table('page')
            ->select('page_id', 'page_title', 'page_view_level', 'page_desc')
            ->orderBy('page_title', 'asc')
            ->get();

        // Map new permission titles via Permission model (old_page_id -> page_title)
        $map = Permission::whereIn('old_page_id', $pages->pluck('page_id')->all())
            ->select('old_page_id', 'page_title')
            ->get()
            ->keyBy('old_page_id');

        // Fetch all pages and map new permission titles
        return $pages->map(function ($feature) use ($map) {
            $feature->new_permission_page_title = $map[$feature->page_id]->page_title ?? null;
            return $feature;
        });
    }

    // =========================================================
    // ==================== Sync (Create/Update) ===============
    // =========================================================

    /**
     * Public unified entrypoint: sync permissions (create/update/delete) for an employee.
     * - Primary DB is authoritative (gets inserts/updates, returns permission_id).
     * - Secondary DB mirrors primary (updates if exists, inserts if missing).
     *
     * @return array Summary counts per side: ['primary'=>['created','updated','deleted','failed'], 'secondary'=>...]
     */
    public function syncPermissions(int $employeeId, array $validatedData, int $savedBy): array
    {
        // Initialize summary
        $summary = [
            'primary' => ['created' => 0, 'updated' => 0, 'deleted' => 0, 'failed' => 0],
            'secondary' => ['created' => 0, 'updated' => 0, 'deleted' => 0, 'failed' => 0],
        ];

        // Normalize new permissions
        $new = $this->preparePermissionData($validatedData);

        // Always backfill before changes, ensures legacy rows sync first
        $this->backfillSecondaryFromPrimary($employeeId, $savedBy, $summary);

        // No new permissions = revoke all existing
        if (empty($new)) {
            $existing = $this->getExistingPermissions($this->mainConn, $employeeId);

            if (!empty($existing)) {
                $allPageIds = array_keys($existing);

                $this->deletePermissions($this->mainConn, $employeeId, $allPageIds, $summary['primary']);
                $this->deletePermissions($this->mirrorConn, $employeeId, $allPageIds, $summary['secondary']);
            }

            return $summary;
        }

        // Normal diff (create/update/delete)
        // Compare existing vs new
        $existing = $this->getExistingPermissions($this->mainConn, $employeeId);
        $diff = $this->diffPermissions($existing, $new);

        // Create + Update
        foreach (array_replace($diff['toCreate'], $diff['toUpdate']) as $pageId => $flags) {
            $this->syncRow($employeeId, (int) $pageId, $this->sanitizeFlags($flags), $savedBy, $summary);
        }

        // Deletes (both DBs)
        if (!empty($diff['deletePageIds'])) {
            $this->deletePermissions($this->mainConn, $employeeId, $diff['deletePageIds'], $summary['primary']);
            $this->deletePermissions($this->mirrorConn, $employeeId, $diff['deletePageIds'], $summary['secondary']);
        }

        // Log::info('[Permission][Sync] Completed', compact('employeeId', 'summary'));
        return $summary;
    }

    /**
     * Ensure all primary rows exist in secondary for this employee.
     */
    protected function backfillSecondaryFromPrimary(int $employeeId, int $savedBy, array &$summary): void
    {
        $primaryRows = DB::connection($this->mainConn)
            ->table($this->table)
            ->where('employee_id', $employeeId)
            ->get();

        if ($primaryRows->isEmpty()) {
            return;
        }

        $secondaryIds = DB::connection($this->mirrorConn)
            ->table($this->table)
            ->where('employee_id', $employeeId)
            ->pluck('permission_id')
            ->all();

        foreach ($primaryRows as $row) {
            if (!in_array($row->permission_id, $secondaryIds, true)) {
                DB::connection($this->mirrorConn)
                    ->table($this->table)
                    ->insert([
                        'permission_id' => $row->permission_id,
                        'employee_id' => $row->employee_id,
                        'page_id' => $row->page_id,
                        'permission_view' => $row->permission_view,
                        'permission_insert' => $row->permission_insert,
                        'permission_update' => $row->permission_update,
                        'permission_delete' => $row->permission_delete,
                        'permission_saved_by' => $savedBy,
                        'permission_save_status' => $row->permission_save_status,
                        'permission_time_stamp' => now(),
                    ]);

                $summary['secondary']['created']++;
                // Log::debug("[Permission][Backfill] Inserted missing row", [
                //     'employee_id' => $employeeId,
                //     'permission_id' => $row->permission_id,
                //     'page_id' => $row->page_id,
                // ]);
            }
        }
    }

    /**
     * Mirror a row from primary to secondary.
     */
    protected function mirrorRowFromPrimary(int $permissionId, int $savedBy, array &$summary): void
    {
        $primaryRow = DB::connection($this->mainConn)
            ->table($this->table)
            ->where('permission_id', $permissionId)
            ->first();

        if (!$primaryRow) {
            return;
        }

        $exists = DB::connection($this->mirrorConn)
            ->table($this->table)
            ->where('permission_id', $permissionId)
            ->exists();

        $row = [
            'employee_id' => $primaryRow->employee_id,
            'page_id' => $primaryRow->page_id,
            'permission_view' => $primaryRow->permission_view,
            'permission_insert' => $primaryRow->permission_insert,
            'permission_update' => $primaryRow->permission_update,
            'permission_delete' => $primaryRow->permission_delete,
            'permission_saved_by' => $savedBy,
            'permission_save_status' => $primaryRow->permission_save_status,
            'permission_time_stamp' => now(),
        ];

        if ($exists) {
            DB::connection($this->mirrorConn)
                ->table($this->table)
                ->where('permission_id', $permissionId)
                ->update($row);

            $summary['secondary']['updated']++;
        } else {
            DB::connection($this->mirrorConn)
                ->table($this->table)
                ->insert(array_merge(['permission_id' => $permissionId], $row));

            $summary['secondary']['created']++;
        }
    }

    /**
     * Back-compat: create flow delegates to unified sync.
     */
    public function createPermissions(int $employeeId, array $validatedData, int $savedBy): array
    {
        return $this->syncPermissions($employeeId, $validatedData, $savedBy);
    }

    /**
     * Back-compat: update flow delegates to unified sync.
     */
    public function updatePermissions(int $employeeId, array $validatedData, int $savedBy): array
    {
        return $this->syncPermissions($employeeId, $validatedData, $savedBy);
    }

    /**
     * Upsert a single (employee_id, page_id) row in primary, then mirror to secondary.
     * - Always writes to primary first; if successful, mirrors to secondary.
     */
    protected function syncRow(int $employeeId, int $pageId, array $permFlags, int $savedBy, array &$summary): void
    {
        // Primary 
        $permissionId = $this->upsertPrimary(
            $this->mainConn,
            $employeeId,
            $pageId,
            $permFlags,
            $savedBy,
            $summary
        );

        if ($permissionId) {
            $this->mirrorRowFromPrimary($permissionId, $savedBy, $summary);
        }
    }

    /**
     * Upsert in primary DB: update if exists, insert if not. Returns permission_id on success.
     */
    protected function upsertPrimary(
        string $conn,
        int $employeeId,
        int $pageId,
        array $permFlags,
        int $savedBy,
        array &$summary
    ): ?int {
        try {
            $existing = DB::connection($conn)
                ->table($this->table)
                ->where('employee_id', $employeeId)
                ->where('page_id', $pageId)
                ->first();

            if ($existing) {
                // Update existing record
                DB::connection($conn)
                    ->table($this->table)
                    ->where('permission_id', $existing->permission_id)
                    ->update(array_merge($permFlags, [
                        'permission_saved_by' => $savedBy,
                        'permission_time_stamp' => now(),
                    ]));

                $summary['primary']['updated']++;

                // Log::debug("[Permission][Primary] Updated", [
                //     'connection' => $conn,
                //     'permission_id' => $existing->permission_id,
                //     'employee_id' => $employeeId,
                //     'page_id' => $pageId,
                //     'flags' => $permFlags,
                // ]);

                return (int) $existing->permission_id;
            }

            // Insert new record
            $row = array_merge([
                'employee_id' => $employeeId,
                'page_id' => $pageId,
                'permission_saved_by' => $savedBy,
                'permission_save_status' => 0,
                'permission_time_stamp' => now(),
            ], $permFlags);

            $permissionId = DB::connection($conn)
                ->table($this->table)
                ->insertGetId($row, 'permission_id');

            $summary['primary']['created']++;

            // Log::debug("[Permission][Primary] Inserted", [
            //     'connection' => $conn,
            //     'permission_id' => $permissionId,
            //     'employee_id' => $employeeId,
            //     'page_id' => $pageId,
            //     'flags' => $permFlags,
            // ]);

            return (int) $permissionId;
        } catch (Throwable $e) {
            $summary['primary']['failed']++;
            Log::error('[Permission][Primary] Upsert failed', [
                'connection' => $conn,
                'employee_id' => $employeeId,
                'page_id' => $pageId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Upsert in secondary DB to always mirror primary.
     * - If exists: update. If missing: insert with same permission_id.
     */
    protected function upsertSecondary(
        string $conn,
        int $permissionId,
        int $employeeId,
        int $pageId,
        array $permFlags,
        int $savedBy,
        array &$summary
    ): void {
        try {
            // Always fetch the authoritative row from primary
            $primaryRow = DB::connection($this->mainConn)
                ->table($this->table)
                ->where('permission_id', $permissionId)
                ->first();

            if (!$primaryRow) {
                // Log::warning("[Permission][Secondary] Skipped: primary row missing", [
                //     'permission_id' => $permissionId,
                //     'employee_id' => $employeeId,
                //     'page_id' => $pageId,
                // ]);
                return;
            }

            // Check if it exists in secondary
            $secondaryRow = DB::connection($conn)
                ->table($this->table)
                ->where('permission_id', $permissionId)
                ->first();

            if ($secondaryRow) {
                // Update existing in secondary to match primary
                DB::connection($conn)
                    ->table($this->table)
                    ->where('permission_id', $permissionId)
                    ->update([
                        'employee_id' => $primaryRow->employee_id,
                        'page_id' => $primaryRow->page_id,
                        'permission_view' => $primaryRow->permission_view,
                        'permission_insert' => $primaryRow->permission_insert,
                        'permission_update' => $primaryRow->permission_update,
                        'permission_delete' => $primaryRow->permission_delete,
                        'permission_saved_by' => $savedBy,
                        'permission_save_status' => $primaryRow->permission_save_status,
                        'permission_time_stamp' => now(),
                    ]);

                $summary['secondary']['updated']++;
                Log::debug("[Permission][Secondary] Updated", [
                    'connection' => $conn,
                    'permission_id' => $permissionId,
                    'employee_id' => $employeeId,
                    'page_id' => $pageId,
                ]);
            } else {
                // Insert fresh row in secondary, mirroring primary
                $mirrorRow = [
                    'permission_id' => $primaryRow->permission_id,
                    'employee_id' => $primaryRow->employee_id,
                    'page_id' => $primaryRow->page_id,
                    'permission_view' => $primaryRow->permission_view,
                    'permission_insert' => $primaryRow->permission_insert,
                    'permission_update' => $primaryRow->permission_update,
                    'permission_delete' => $primaryRow->permission_delete,
                    'permission_saved_by' => $savedBy,
                    'permission_save_status' => $primaryRow->permission_save_status,
                    'permission_time_stamp' => now(),
                ];

                DB::connection($conn)
                    ->table($this->table)
                    ->insert($mirrorRow);

                $summary['secondary']['created']++;
                // Log::debug("[Permission][Secondary] Inserted (backfill)", [
                //     'connection' => $conn,
                //     'permission_id' => $permissionId,
                //     'employee_id' => $employeeId,
                //     'page_id' => $pageId,
                // ]);
            }

        } catch (Throwable $e) {
            $summary['secondary']['failed']++;
            Log::error('[Permission][Secondary] Upsert failed', [
                'connection' => $conn,
                'permission_id' => $permissionId,
                'employee_id' => $employeeId,
                'page_id' => $pageId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Deletes permissions from a specific database connection.
     */
    protected function deletePermissions(string $conn, int $employeeId, array $pageIds, array &$summary): void
    {
        if (empty($pageIds)) {
            return;
        }

        try {
            $deleted = DB::connection($conn)
                ->table($this->table)
                ->where('employee_id', $employeeId)
                ->whereIn('page_id', $pageIds)
                ->delete();

            $summary['deleted'] += (int) $deleted;

            // Log::info('[Permission][Sync] Deleted permissions.', [
            //     'connection' => $conn,
            //     'employee_id' => $employeeId,
            //     'deleted' => $deleted,
            // ]);
        } catch (Throwable $e) {
            $summary['failed']++;
            Log::error('[Permission][Sync] Delete failed.', [
                'connection' => $conn,
                'employee_id' => $employeeId,
                'page_ids' => $pageIds,
                'error' => $e->getMessage(),
            ]);
        }
    }

    // =========================================================
    // =================== Show / Edit Helpers =================
    // =========================================================

    /**
     * Details for the show view.
     * Returns: ['employee'=>object, 'permissions'=>Collection]
     */
    public function getPermissionDetails(int $employeeId): array
    {
        // Fetch permissions with related context (member, employee, transport, page)
        $permissions = DB::table('permission')
            ->leftJoin('member', 'permission.employee_id', '=', 'member.member_id')
            ->leftJoin('employee', 'permission.employee_id', '=', 'employee.employee_id')
            ->leftJoin('transport', 'employee.transport_id', '=', 'transport.transport_id')
            ->leftJoin('page', 'permission.page_id', '=', 'page.page_id')
            ->select(
                'permission.*',
                'member.member_login',
                'page.page_title',
                DB::raw('COALESCE(employee.employee_name, "N/A") AS employee_name'),
                DB::raw('COALESCE(transport.transport_name, "N/A") AS transport_name')
            )
            ->where('permission.employee_id', $employeeId)
            ->orderBy('page.page_title', 'asc') // Alphabetical order by page title
            ->get();

        // Summarize permission count (unique pages)
        $permissionCount = $permissions->pluck('page_id')->unique()->count();

        // Extract employee info from first record (if exists)
        $first = $permissions->first();

        $employee = (object) [
            'employee_id' => $employeeId,
            'employee_name' => $first->employee_name ?? 'N/A',
            'member_login' => $first->member_login ?? 'N/A',
            'transport_name' => $first->transport_name ?? 'N/A',
            'permission_count' => $permissionCount,
        ];

        // Structure data for the view
        return [
            'employee' => $employee,
            'permissions' => $permissions,
        ];
    }

    /**
     * Data for edit view: merged all pages + current flags (+ new titles if mapped).
     */
    public function getPermissionsForEdit(int $employeeId): array
    {
        // Fetch all pages
        $allPages = DB::table('page')
            ->select('page_id', 'page_title')
            ->orderBy('page_id', 'asc')
            ->get()
            ->keyBy('page_id');

        // Fetch permissions for the employee
        $permissions = DB::table('permission')
            ->leftJoin('member', 'permission.employee_id', '=', 'member.member_id')
            ->leftJoin('employee', 'permission.employee_id', '=', 'employee.employee_id')
            ->leftJoin('transport', 'employee.transport_id', '=', 'transport.transport_id')
            ->leftJoin('page', 'permission.page_id', '=', 'page.page_id')
            ->select(
                'permission.*',
                'member.member_login',
                'page.page_title as old_page_title',
                DB::raw('COALESCE(employee.employee_name, "N/A") AS employee_name'),
                DB::raw('COALESCE(transport.transport_name, "N/A") AS transport_name')
            )
            ->where('permission.employee_id', $employeeId)
            ->get()
            ->keyBy('page_id');

        // Extract old page IDs from the permissions collection
        $oldPageIds = $permissions->keys()->all();

        // Assuming Permission model corresponds to the new permissions table
        $newPageTitles = Permission::whereIn('old_page_id', $oldPageIds)
            ->select('old_page_id', 'page_title as new_page_title')
            ->get()
            ->keyBy('old_page_id');

        // Attach new page titles to permissions
        foreach ($permissions as $oldPageId => $perm) {
            $perm->new_page_title = $newPageTitles[$oldPageId]->new_page_title ?? null;
        }

        // Extract employee info from first permission record
        $firstPerm = $permissions->first();
        $employee = (object) [
            'employee_id' => $employeeId,
            'employee_name' => $firstPerm->employee_name ?? 'N/A',
            'member_login' => $firstPerm->member_login ?? 'N/A',
            'transport_name' => $firstPerm->transport_name ?? 'N/A',
            'permission_count' => $permissions->count(),
        ];

        // Merge permissions with all pages
        $mergedPermissions = $allPages->map(function ($page) use ($permissions) {
            $perm = $permissions->get($page->page_id);
            return (object) [
                'page_id' => $page->page_id,
                'page_title' => $page->page_title, // old page title
                'new_page_title' => $perm->new_page_title ?? null, // new title from current DB
                'permission_view' => $perm->permission_view ?? 0,
                'permission_insert' => $perm->permission_insert ?? 0,
                'permission_update' => $perm->permission_update ?? 0,
                'permission_delete' => $perm->permission_delete ?? 0,
            ];
        })
            ->sortBy('page_title') // ensure final merged list is alphabetical
            ->values();

        return [
            'employee' => $employee,
            'transport' => $employee->transport_name,
            'permissions' => $mergedPermissions,
            'rawPermissions' => $permissions,
        ];
    }

    // =========================================================
    // ============== Session Hydration & Runtime ==============
    // =========================================================

    /**
     * (Re)build session for the current member.
     * Session key: 'user_permissions'
     */
    public static function hydrate(): array
    {
        $user = Auth::guard('member')->user();

        if (!$user) {
            $payload = [
                'employee' => [
                    'employee_id' => 0,
                    'employee_name' => 'Guest',
                    'member_login' => 'N/A',
                    'transport_name' => 'N/A',
                    'permission_count' => 0,
                ],
                'permissions' => [],
                'permissions_map' => [],   // page_id => abilities
                'last_ts' => null, // DB last updated_at/time_stamp
            ];
            session(['user_permissions' => $payload]);
            return $payload;
        }

        $userId = (int) $user->member_id; // if different from employee_id, adjust join below

        // Employee info
        $employee = DB::table('employee')
            ->leftJoin('transport', 'employee.transport_id', '=', 'transport.transport_id')
            ->select('employee.employee_id', 'employee.employee_name', 'transport.transport_name')
            ->where('employee.employee_id', $userId)
            ->first();

        // Permissions + page meta (your tables/columns)
        $permissions = DB::table('permission')
            ->leftJoin('page', 'permission.page_id', '=', 'page.page_id')
            ->select(
                'permission.permission_id',
                'permission.page_id',
                'permission.permission_view',
                'permission.permission_insert',
                'permission.permission_update',
                'permission.permission_delete',
                'permission.permission_save_status',
                'permission.permission_time_stamp',
                'page.page_title',
                'page.page_name',
                'page.page_type_id'
            )
            ->where('permission.employee_id', $userId)
            ->orderBy('page.page_title', 'asc')
            ->get();

        // Build quick lookup map for runtime checks
        $map = [];

        foreach ($permissions as $p) {
            $pid = (int) $p->page_id;
            $map[$pid] = [
                'view' => (int) $p->permission_view === 1,
                'insert' => (int) $p->permission_insert === 1,
                'update' => (int) $p->permission_update === 1,
                'delete' => (int) $p->permission_delete === 1,
                'title' => $p->page_title,
                'name' => $p->page_name,
            ];
        }

        // Track latest change in DB to auto-refresh later
        $lastTs = DB::table('permission')
            ->where('employee_id', $userId)
            ->max('permission_time_stamp');

        $payload = [
            'employee' => [
                'employee_id' => $employee->employee_id ?? $userId,
                'employee_name' => $employee->employee_name ?? 'N/A',
                'member_login' => $user->member_login ?? 'N/A',
                'transport_name' => $employee->transport_name ?? 'N/A',
                'permission_count' => collect($permissions)->unique('page_id')->count(),
            ],
            'permissions' => $permissions->toArray(),
            'permissions_map' => $map,
            'last_ts' => $lastTs,
        ];

        session(['user_permissions' => $payload]);
        return $payload;
    }

    /**
     * Refresh session only if DB changed since last hydration.
     */
    public static function refreshIfStale(): void
    {
        $user = Auth::guard('member')->user();

        if (!$user)
            return;

        $userId = (int) $user->member_id;
        $session = session('user_permissions');
        $sessionTs = $session['last_ts'] ?? null;

        $dbTs = DB::table('permission')
            ->where('employee_id', $userId)
            ->max('permission_time_stamp');

        if ($dbTs !== $sessionTs) {
            self::hydrate();
        }
    }

    /**
     * Quick ability check from session cache.
     */
    public static function can(int $pageId, string $ability): bool
    {
        $map = session('user_permissions.permissions_map', []);
        return !empty($map[$pageId][strtolower($ability)] ?? false);
    }
}