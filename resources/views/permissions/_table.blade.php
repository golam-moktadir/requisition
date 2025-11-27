<div id="listArea" class="relative">
    <!-- Loader Overlay (hidden by default, shown during AJAX calls) -->
    <div id="loadingOverlay" class="absolute inset-0 bg-white/80 flex items-center justify-center hidden z-10">
        <div class="flex flex-col items-center gap-2">
            <!-- Spinner -->
            <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
            </svg>
            <span class="text-sm text-gray-700 font-medium">Loading data...</span>
        </div>
    </div>

    <!-- Table wrapper -->
    <div class="overflow-x-auto">
        <table class="tbl table-fixed w-full text-sm text-center align-middle" aria-label="Permission Table">
            <thead>
                <tr>
                    <th rowspan="2" class="bg-legacy-blue-soft text-white border py-0.5 w-[40px]">No.</th>
                    <th rowspan="2" class="bg-legacy-blue-soft text-white border py-0.5 w-[220px]">Employee Name</th>
                    <th rowspan="2" class="bg-legacy-blue-soft text-white border py-0.5 w-[160px]">Login</th>
                    <th rowspan="2" class="bg-legacy-blue-soft text-white border py-0.5 w-[110px]">Permission Count
                    </th>
                    <th colspan="1" class="bg-legacy-blue-soft text-white border py-0.5 w-[80px]">Options</th>
                </tr>
                <tr>
                    <th class="bg-legacy-grey text-white border py-0.5 w-[80px]">Edit</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($rows as $i => $row)
                    <tr class="hover:bg-gray-200">
                        <td class="bg-legacy-cell py-1">{{ $rows->firstItem() + $i }}</td>
                        <td class="bg-legacy-cell text-left px-2 py-1 truncate" title="{{ $row->employee_name }}">
                            {{ $row->employee_name }}
                        </td>
                        <td class="bg-legacy-cell px-2 py-1 text-left">{{ $row->member_login }}</td>
                        <td class="bg-legacy-cell py-1 text-center">{{ $row->permission_count }}</td>
                        <td class="bg-legacy-cell py-1">
                            <a href="{{ route($resource . '.edit', $encryptor->encrypt($row->member_id)) }}"
                                class="text-blue-700 hover:text-blue-900 font-medium">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="bg-legacy-cell py-2 text-center text-gray-600">
                            No Permissions Found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div
            class="mt-3 px-2 py-2 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm border-t border-gray-200 pt-3">
            <!-- Summary -->
            <div class="text-gray-600">
                @if ($rows->total() > 0)
                    Showing
                    <span class="font-semibold">{{ $rows->firstItem() }}</span> –
                    <span class="font-semibold">{{ $rows->lastItem() }}</span>
                    of
                    <span class="font-semibold">{{ $rows->total() }}</span>
                    results
                @else
                    No results found
                @endif
            </div>

            <!-- Pagination -->
            <div class="pagination-area">
                {{-- Laravel Tailwind pagination, limited to 5–6 pages with ellipses --}}
                {{ $rows->onEachSide(2)->links('pagination::tailwind') }}
            </div>
        </div>

    </div>
</div>