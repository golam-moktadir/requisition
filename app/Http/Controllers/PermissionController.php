<?php

namespace App\Http\Controllers;

use App\Helpers\ShortEncryptor;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\User;
use App\Services\PermissionService;
use App\Services\UserSessionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use App\Traits\RedirectWithMessage;

class PermissionController extends Controller
{
    use RedirectWithMessage;

    protected PermissionService $service;
    protected ShortEncryptor $encryptor;

    protected string $resource = 'permissions';
    protected string $featureName = 'Permission';
    protected string $featurePluralName = 'Permissions';
    protected string $modelVariable = 'permission';

    protected int $perPage = 20;

    // Constants for Configuration 
    private const PAGE_ID = 19;

    /**
     * Initialize the PermissionController.
     *
     * Applies middleware for authentication, fresh permissions, 
     * and page-specific access control (page_id: 19).
     *
     * @param PermissionService $service   Handles business logic for permissions.
     * @param ShortEncryptor    $encryptor Utility for encrypting/decrypting IDs.
     */

    /**
     * Display a paginated list of permissions.
     */
    public function index()
    {
        $data['title'] = 'User Permission';
        $data['result'] = User::all();
        return view('permissions.index', $data);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // [
        //     'transportOptions' => $this->service->getTransportOptions(),
        //     'excludeMembers' => $this->service->getMembersWithoutPermissions(),
        //     'features' => $this->service->getFeaturesForCreate(),
        //     'menuPageIds' => $this->service->getAllMenuPageIds(),
        // ]
        $data['title'] = 'User Permission';
        $data['features'] = DB::table('page')->get();
        $data['result'] = User::all();
        return view("permissions.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $validated = $request->validated();
        $employeeId = (int) $validated['employee_id'];
        $savedBy = Auth::guard('member')->id();

        try {
            $this->service->createPermissions($employeeId, $validated, $savedBy);

            return redirect()->route("{$this->resource}.index")
                ->with('success', "{$this->featureName} added successfully.");
        } catch (\Throwable $e) {
            Log::error("[{$this->featureName}][Store] Failed", ['error' => $e->getMessage()]);
            return back()->with('error', "Failed to add {$this->featureName}.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function show(string $encryptedId): View
    {
        $employeeId = (int) $this->encryptor::decrypt($encryptedId);
        $result = $this->service->getPermissionDetails($employeeId);

        return view("{$this->resource}.show", compact('result'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $encryptedId): View
    {
        $employeeId = (int) $this->encryptor::decrypt($encryptedId);
        $result = $this->service->getPermissionsForEdit($employeeId);

        return view("{$this->resource}.edit", [
            'employee' => $result['employee'],
            'transport' => $result['employee']->transport_name ?? 'N/A',
            'permissions' => $result['permissions'],
            'rawPermissions' => $result['rawPermissions'],
            'encryptedId' => $encryptedId,
            'menuPageIds' => $this->service->getAllMenuPageIds(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, string $encryptedId)
    {
        $employeeId = (int) $this->encryptor::decrypt($encryptedId);
        $savedBy = Auth::guard('member')->id();
        $validated = $request->validated();

        try {
            // Old snapshot
            $old = DB::table('permission')
                ->where('employee_id', $employeeId)
                ->get()
                ->keyBy('page_id');

            // Update
            $this->service->updatePermissions($employeeId, $validated, $savedBy);

            // Force logout this user from all devices (if currently active)
            UserSessionManager::forceLogout($employeeId);

            // If this same employee is the current logged-in user → destroy active session too
            if (Auth::check() && Auth::id() === $employeeId) {
                Auth::logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                Log::info('[PermissionUpdate] Current session invalidated after self permission update', [
                    'employee_id' => $employeeId,
                ]);
            }

            // Set cache flag for middleware to show user message
            // Cache::put(
            //     "force_logout_user_{$employeeId}",
            //     'Your permissions were updated by an administrator. Please log in again.',
            //     now()->addMinutes(1)
            // );

            Log::info("[PermissionUpdate] Forced logout triggered after permission update", [
                'employee_id' => $employeeId,
                'updated_by' => $savedBy,
            ]);

            // New snapshot
            $new = DB::table('permission')
                ->join('page', 'page.page_id', '=', 'permission.page_id')
                ->where('permission.employee_id', $employeeId)
                ->select('page.page_title', 'permission.*')
                ->get();

            // Diff summary (ideally move this logic to service)
            $changes = [];
            foreach ($new as $perm) {
                foreach (['view', 'insert', 'update', 'delete'] as $a) {
                    $col = "permission_$a";
                    $oldVal = $old[$perm->page_id]->$col ?? 0;
                    if ($perm->$col != $oldVal) {
                        $changes[] = "{$perm->page_title} - " . ucfirst($a) . ' ' . ($perm->$col ? '✓' : '✗');
                    }
                }
            }

            return redirect()
                ->route("{$this->resource}.show", $this->encryptor->encrypt($employeeId))
                ->with('success', [
                    'msg' => "{$this->featureName} updated successfully.",
                    'changes' => $changes,
                ]);
        } catch (\Throwable $e) {
            Log::error("[{$this->featureName}][Update] Failed", ['error' => $e->getMessage()]);
            return back()->with('error', "Failed to update {$this->featureName}.");
        }

    }

    /**
     * Remove a permission (future implementation).
     */
    public function destroy(string $encryptedId)
    {
        try {
            $employeeId = (int) $this->encryptor::decrypt($encryptedId);
            $savedBy = Auth::guard('member')->id();

            // $this->service->deletePermissions($employeeId, $savedBy);

            return redirect()->route("{$this->resource}.index")
                ->with('success', "{$this->featureName} deleted successfully.");
        } catch (\Throwable $e) {
            Log::error("[{$this->featureName}][Destroy] Failed", [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', "Failed to delete {$this->featureName}.");
        }
    }

    /**
     * Show current logged-in user's permissions.
     */
    public function getCurrentPermissionsJson()
    {
        // This will also store into session inside service
        $data = $this->service->hydrate();

        // Return JSON to frontend
        return response()->json([
            'status' => 'success',
            'employee' => $data['employee'],
            'permissions' => $data['permissions'],
            'session_saved' => session()->has('user_permissions'),
        ]);
    }
}
