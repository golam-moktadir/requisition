<?php

namespace App\Traits;

use App\Helpers\ShortEncryptor;
use Exception;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * DataTableTrait
 *
 * Provides reusable server-side processing for jQuery DataTables.
 * Key features:
 * - Global and field-specific search
 * - Custom filtering (e.g., status, type)
 * - Column sorting
 * - Pagination
 * - Secure ID encryption
 * - Flexible column formatting
 * - Comprehensive error handling
 * - Supports Eloquent and Query Builder
 *
 * Usage:
 * 1. Include in a controller: `use DataTableTrait;`
 * 2. Define `getDataTableConfig()` in the controller to return configuration
 * 3. Call `handleDataTableRequest($request, $this->getDataTableConfig())` in the `index` method
 *
 * Configuration:
 * - model: Eloquent model class (e.g., OperationalStaff::class)
 * - baseQuery: Optional base query builder (overrides model)
 * - searchableFields: Array mapping request params to columns (e.g., ['searchName' => 'full_name'])
 * - sortableColumns: Array of sortable column names
 * - filters: Array of custom filter configs (e.g., ['searchStatus' => ['column' => 'status']])
 * - displayColumns: Array of column formatters (e.g., ['full_name' => fn($item) => $item->full_name])
 * - buttons: Action button config (show, edit, delete, custom)
 * - perPage: Default records per page
 * - idColumn: Primary key column (default: 'id')
 *
 * @package App\Traits
 */
trait DataTableTrait
{
    /**
     * Handle AJAX DataTable request
     *
     * Processes server-side DataTable requests with search, filtering, sorting, and pagination.
     *
     * @param Request $request Incoming request with DataTable parameters:
     *   - draw: int - DataTables draw counter
     *   - start: int - Pagination start index
     *   - length: int - Records per page
     *   - order[0][column]: int - Column index to sort by
     *   - order[0][dir]: string - Sort direction (asc/desc)
     *   - search[value]: string - Global search term
     *   - Custom params (e.g., searchName, searchStatus) based on config
     * @param array $config DataTable configuration (from getDataTableConfig)
     * @return JsonResponse Structured response:
     *   - draw: int - Echoes request draw counter
     *   - recordsTotal: int - Total records
     *   - recordsFiltered: int - Filtered records
     *   - data: array - Formatted records
     *   - error: string (optional) - Error message
     */
    protected function handleDataTableRequest(Request $request, array $config): JsonResponse
    {
        try {
            // Merge with default config to ensure required keys
            $config = array_merge($this->getDefaultDataTableConfig(), $config);

            // Initialize base query
            $query = $this->getBaseQuery($config);

            // Get total records before filtering
            $totalRecords = (clone $query)->count();

            // Apply global search, field-specific search, and custom filters
            $this->applySearchFilters($query, $request, $config);
            $this->applyCustomFilters($query, $request, $config);

            // Get filtered records count
            $filteredRecords = (clone $query)->count();

            // Apply sorting and pagination
            $this->applySorting($query, $request, $config);
            $results = $this->paginateResults($query, $request, $config);

            // Format results for DataTables
            $formattedData = $this->formatResults($results, $config);

            return response()->json([
                'draw' => (int) $request->input('draw', 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $formattedData,
            ]);
        } catch (Exception $e) {
            Log::error("DataTable error for {$config['featureName']}: {$e->getMessage()}", [
                'request' => $request->all(),
                'config' => $this->sanitizeConfigForLogging($config),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'draw' => (int) $request->input('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => "Failed to load {$config['featureName']} data.",
            ], 500);
        }
    }

    /**
     * Get default DataTable configuration
     *
     * Provides fallback values for required configuration keys.
     *
     * @return array Default configuration
     */
    protected function getDefaultDataTableConfig(): array
    {
        return [
            'featureName' => 'Resource',
            'resource' => 'resources',
            'model' => null,
            'baseQuery' => null,
            'searchableFields' => [],
            'sortableColumns' => ['id'],
            'filters' => [],
            'displayColumns' => [
                'id' => fn($item) => $item->id,
            ],
            'buttons' => [
                'show' => false,
                'edit' => false,
                'delete' => false,
                'custom' => [],
            ],
            'perPage' => 10,
            'idColumn' => 'id',
        ];
    }

    /**
     * Initialize base query from configuration
     *
     * Uses either the provided baseQuery or builds one from the model.
     *
     * @param array $config DataTable configuration
     * @return EloquentBuilder|QueryBuilder Base query
     * @throws Exception If neither model nor baseQuery is provided
     */
    protected function getBaseQuery(array $config): EloquentBuilder|QueryBuilder
    {
        if ($config['baseQuery'] instanceof EloquentBuilder || $config['baseQuery'] instanceof QueryBuilder) {
            return $config['baseQuery'];
        }

        if ($config['model'] && class_exists($config['model'])) {
            return $config['model']::query();
        }

        throw new Exception('DataTable configuration must specify a valid model or baseQuery.');
    }

    /**
     * Apply global and field-specific search filters
     *
     * Handles global search (search[value]) and field-specific searches (e.g., searchName).
     *
     * @param EloquentBuilder|QueryBuilder $query Query to modify
     * @param Request $request HTTP request
     * @param array $config DataTable configuration
     */
    protected function applySearchFilters(EloquentBuilder|QueryBuilder $query, Request $request, array $config): void
    {
        // Global search across searchable fields
        if ($globalSearch = trim($request->input('search.value', ''))) {
            $query->where(function ($q) use ($globalSearch, $config) {
                foreach ($config['searchableFields'] as $column) {
                    $q->orWhereRaw("LOWER({$column}) LIKE ?", ['%' . strtolower($globalSearch) . '%']);
                }
            });
        }

        // Field-specific searches
        foreach ($config['searchableFields'] as $param => $column) {
            if ($searchTerm = trim($request->input($param, ''))) {
                $query->whereRaw("LOWER({$column}) LIKE ?", ['%' . strtolower($searchTerm) . '%']);
            }
        }
    }

    /**
     * Apply custom filters (e.g., status, type)
     *
     * Supports exact matches or custom filter handlers.
     *
     * @param EloquentBuilder|QueryBuilder $query Query to modify
     * @param Request $request HTTP request
     * @param array $config DataTable configuration
     */
    protected function applyCustomFilters(EloquentBuilder|QueryBuilder $query, Request $request, array $config): void
    {
        foreach ($config['filters'] as $param => $filterConfig) {
            if ($request->filled($param)) {
                if (isset($filterConfig['handler']) && is_callable($filterConfig['handler'])) {
                    $filterConfig['handler']($query, $request->input($param));
                } else {
                    $query->where($filterConfig['column'], $request->input($param));
                }
            }
        }
    }

    /**
     * Apply sorting to the query
     *
     * Uses the order[0][column] and order[0][dir] parameters.
     *
     * @param EloquentBuilder|QueryBuilder $query Query to modify
     * @param Request $request HTTP request
     * @param array $config DataTable configuration
     */
    protected function applySorting(EloquentBuilder|QueryBuilder $query, Request $request, array $config): void
    {
        // Get column index and direction from request, with defaults
        $columnIndex = (int) $request->input('order.0.column', 0);
        $direction = $request->input('order.0.dir', 'asc');

        // Validate direction value
        $direction = in_array(strtolower($direction), ['asc', 'desc']) ? strtolower($direction) : 'asc';

        // Get the column name from config or fallback to first column
        $column = $config['sortableColumns'][$columnIndex] ?? $config['sortableColumns'][0];
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        $query->orderBy($column, $direction);
    }

    /**
     * Paginate query results
     *
     * Applies offset and limit based on start and length parameters.
     *
     * @param EloquentBuilder|QueryBuilder $query Query to paginate
     * @param Request $request HTTP request
     * @param array $config DataTable configuration
     * @return \Illuminate\Support\Collection Paginated results
     */
    protected function paginateResults(EloquentBuilder|QueryBuilder $query, Request $request, array $config): \Illuminate\Support\Collection
    {
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', $config['perPage']);

        return $query->offset($start)->limit($length)->get();
    }

    /**
     * Format results for DataTable response
     *
     * Applies column formatters and renders action buttons.
     *
     * @param \Illuminate\Support\Collection $results Query results
     * @param array $config DataTable configuration
     * @return array Formatted data
     */
    protected function formatResults(\Illuminate\Support\Collection $results, array $config): array
    {
        $encryptor = app(ShortEncryptor::class);

        return $results->map(function ($item) use ($config, $encryptor) {
            $row = [];

            // Apply column formatters
            foreach ($config['displayColumns'] as $column => $formatter) {
                $row[$column] = is_callable($formatter)
                    ? $formatter($item)
                    : ($item->{$column} ?? null);
            }

            // Prepare custom buttons
            $customButtons = [];
            if (!empty($config['buttons']['custom'])) {
                foreach ($config['buttons']['custom'] as $btnConfig) {
                    $customButtons[] = [
                        'label' => $btnConfig['label_callback']($item),
                        'route' => $btnConfig['route_callback']($item),
                        'class' => $btnConfig['class_callback']($item),
                        'icon' => $btnConfig['icon_callback']($item),
                        'confirm' => $btnConfig['confirm'] ?? false,
                        'confirm_text' => isset($btnConfig['confirm_text_callback'])
                            ? $btnConfig['confirm_text_callback']($item)
                            : 'Are you sure?',
                        'method' => $btnConfig['method'] ?? 'POST',
                        'title' => isset($btnConfig['title_callback']) ? $btnConfig['title_callback']($item) : ($btnConfig['title'] ?? 'Action'),
                    ];
                }
            }

            // Render action buttons
            if (!empty($config['buttons'])) {

                $row['actions'] = view('components.datatable.actions', [
                    'resource' => $config['resource'],
                    'id' => $encryptor->encrypt($item->{$config['idColumn']}),
                    'show' => $config['buttons']['show'] ?? false,
                    'edit' => $config['buttons']['edit'] ?? false,
                    'delete' => $config['buttons']['delete'] ?? false,
                    'customButtons' => $customButtons,
                ])->render();
            }

            return $row;
        })->toArray();
    }

    /**
     * Sanitize config for logging
     *
     * Removes sensitive data to prevent logging credentials or large queries.
     *
     * @param array $config DataTable configuration
     * @return array Sanitized configuration
     */
    protected function sanitizeConfigForLogging(array $config): array
    {
        $sanitized = $config;
        unset($sanitized['baseQuery'], $sanitized['model']);
        return $sanitized;
    }
}