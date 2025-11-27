@extends('layouts.app')

@section('title', 'Edit Permission')

@push('styles')
    <!-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/select2.min.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('assets/css/permissions/permission-create-edit.css') }}" as="style">

    <style>
        /* Pointer on hover for checkboxes */
        .table .checkbox-input {
            cursor: pointer;
        }

        /* Highlight entire row on hover */
        #permission-table tbody tr:hover td {
            background-color: #e5e7eb !important;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto p-2">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="px-4 py-1 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <!-- Left: Title & Employee Info -->
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center flex-wrap gap-2">
                        <i class="fas fa-edit text-blue-600" aria-hidden="true"></i>
                        <span>Edit Permission</span>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            {{ $employee->employee_name }} ({{ $employee->member_login }})
                        </span>
                        <span
                            class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                            {{ $employee->permission_count ?? $permissions->count() }} total
                        </span>
                    </h1>
                    <!-- Right: Actions -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('permissions.show', \App\Helpers\ShortEncryptor::encrypt($employee->employee_id)) }}"
                            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-white rounded-md shadow-sm transition-colors bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-500">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('permissions.index') }}"
                            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-md shadow-sm transition-colors bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

            </div>

            <div class="p-2">
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded-md mb-4" role="alert" aria-live="polite">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Use PUT method and encryptedId --}}
                <form method="POST" action="{{ route('permissions.update', $encryptedId) }}" id="permission-form"
                    class="space-y-1 px-2" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3 p-3 rounded-md bg-blue-50 dark:bg-gray-700 border border-blue-200 dark:border-gray-600">
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-snug">
                            <strong class="font-semibold text-gray-900 dark:text-white">Note:</strong>
                            Items displayed in <strong class="font-semibold">bold</strong> represent
                            <em>main menus or parent modules</em>, while the indented items underneath
                            indicate their <em>sub-menus or specific permission options</em>.
                        </p>
                    </div>

                    {{-- Hidden fields for transport and employee info --}}
                    <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
                    <input type="hidden" name="transport_name" value="{{ $employee->transport_name }}">

                    {{-- Transport & Employee Info --}}
                    <div class="grid grid-cols-[30%_minmax(0,_1fr)] text-xs border-b border-white">
                        {{-- Transport Row --}}
                        <div class="py-1 px-2 border-r flex items-center justify-end font-semibold bg-[#999999] text-white">
                            Transport Name
                        </div>
                        <div class="py-1 px-2 flex items-center bg-[#CCCCCC]">
                            <span class="text-black font-bold">
                                {{ $employee->transport_name ?? 'N/A' }}
                            </span>
                        </div>
                        {{-- Employee Row --}}
                        <div
                            class="py-1 px-2 border-r border-t border-white flex items-center justify-end font-semibold bg-[#999999] text-white">
                            Employee Name
                        </div>
                        <div class="py-1 px-2 border-t border-white flex items-center bg-[#CCCCCC]">
                            <span class="text-black font-bold">
                                {{ $employee->employee_name }} ({{ $employee->member_login }})
                            </span>
                        </div>
                    </div>

                    {{-- Permission Matrix --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle text-center" id="permission-table">
                            <thead>
                                <tr id="thead-row-1">
                                    <th colspan="2" class="bg-legacy-blue border border-white text-center">Feature Info
                                    </th>
                                    <th colspan="4" class="bg-legacy-blue border border-white text-center">Permission
                                        Level</th>
                                    <th rowspan="2" width="60"
                                        class="bg-legacy-blue text-center align-middle border border-white">
                                        <label class="inline-flex items-center gap-2" for="select-all">
                                            All
                                            <input type="checkbox" id="select-all" class="checkbox-input"
                                                aria-label="Toggle all permissions">
                                        </label>
                                    </th>
                                </tr>
                                <tr id="thead-row-2">
                                    <th width="40" class="bg-legacy-blue border border-white">#</th>
                                    <th class="bg-legacy-blue border border-white text-left">Page Name</th>
                                    <th class="bg-legacy-blue-soft border border-white">
                                        <label class="inline-flex items-center gap-2">
                                            <input type="checkbox" class="checkbox-input column-select" data-column="view"
                                                aria-label="Toggle column view"> View
                                        </label>
                                    </th>
                                    <th class="bg-legacy-blue-soft border border-white">
                                        <label class="inline-flex items-center gap-2">
                                            <input type="checkbox" class="checkbox-input column-select" data-column="insert"
                                                aria-label="Toggle column insert"> Insert
                                        </label>
                                    </th>
                                    <th class="bg-legacy-blue-soft border border-white">
                                        <label class="inline-flex items-center gap-2">
                                            <input type="checkbox" class="checkbox-input column-select" data-column="update"
                                                aria-label="Toggle column update"> Update
                                        </label>
                                    </th>
                                    <th class="bg-legacy-blue-soft border border-white">
                                        <label class="inline-flex items-center gap-2">
                                            <input type="checkbox" class="checkbox-input column-select" data-column="delete"
                                                aria-label="Toggle column delete"> Delete
                                        </label>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($permissions as $feature)
                                    @php
    $pid = (int) $feature->page_id;
    $isMenuPage = in_array($pid, $menuPageIds ?? [], true); 
                                    @endphp

                                    <tr id="row-{{ $pid }}">
                                        <td class="border border-gray-200 bg-[#CCCCCC]">{{ $loop->iteration }}</td>

                                        <td class="text-left border border-gray-200 bg-[#CCCCCC]">
                                            @if ($isMenuPage)
                                                <strong>{{ $feature->page_title }}</strong>
                                            @else
                                                {{ $feature->page_title }}
                                            @endif
                                        </td>

                                        <td class="border border-gray-200 bg-[#CCCCCC]">
                                            <input type="checkbox" name="view_ids[]" value="{{ $pid }}"
                                                class="checkbox-input perm-checkbox view-checkbox" data-row="row-{{ $pid }}"
                                                data-type="view" @checked($feature->permission_view)
                                                aria-label="View for {{ $feature->page_title }}">
                                        </td>
                                        <td class="border border-gray-200 bg-[#CCCCCC]">
                                            <input type="checkbox" name="insert_ids[]" value="{{ $pid }}"
                                                class="checkbox-input perm-checkbox insert-checkbox" data-row="row-{{ $pid }}"
                                                data-type="insert" @checked($feature->permission_insert)
                                                aria-label="Insert for {{ $feature->page_title }}">
                                        </td>
                                        <td class="border border-gray-200 bg-[#CCCCCC]">
                                            <input type="checkbox" name="update_ids[]" value="{{ $pid }}"
                                                class="checkbox-input perm-checkbox update-checkbox" data-row="row-{{ $pid }}"
                                                data-type="update" @checked($feature->permission_update)
                                                aria-label="Update for {{ $feature->page_title }}">
                                        </td>
                                        <td class="border border-gray-200 bg-[#CCCCCC]">
                                            <input type="checkbox" name="delete_ids[]" value="{{ $pid }}"
                                                class="checkbox-input perm-checkbox delete-checkbox" data-row="row-{{ $pid }}"
                                                data-type="delete" @checked($feature->permission_delete)
                                                aria-label="Delete for {{ $feature->page_title }}">
                                        </td>

                                        <td class="border border-gray-200 bg-[#CCCCCC]">
                                            <input type="checkbox" class="checkbox-input row-select" data-row="row-{{ $pid }}"
                                                aria-label="Toggle all for {{ $feature->page_title }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Actions --}}
                    <div class="bg-[#CCCCCC] p-1 flex justify-center items-center gap-3 border-t border-white">
                        <button type="submit"
                            class="bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white px-4 py-1 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 text-sm font-semibold border border-gray-400 dark:border-gray-500 transition-colors">
                            Update
                        </button>
                        <a href="{{ route('permissions.index') }}"
                            class="bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white px-4 py-1 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 text-sm font-semibold border border-gray-400 dark:border-gray-500 transition-colors">
                            View Records
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Vendor JavaScript -->
    <script src="{{ asset('assets/vendor/jquery/jquery-3-7-1.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert/package/dist/sweetalert2.min.js') }}"></script>
    <!-- Custom Script -->
    <script>

        // --- Confirm before submitting if no permissions are checked ---
        $('#permission-form').on('submit', function (e) {
            const anyChecked = $('.perm-checkbox:checked').length > 0;

            if (!anyChecked) {
                const proceed = confirm("⚠️ You are about to revoke ALL permissions for this employee. Do you want to continue?");
                if (!proceed) {
                    e.preventDefault(); // Stop submission if Cancel clicked
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Select2
            $('.transport-select, .employee-select').select2({
                placeholder: 'Select option',
                allowClear: true,
                width: '100%'
            });

            // Logger utility for debugging
            const LOG = {
                enabled: true, // set false to disable
                info(msg, data = null) {
                    if (this.enabled) console.log(`%c[Permission][INFO] ${msg}`,
                        "color:#1a73e8;font-weight:bold;", data);
                },
                warn(msg, data = null) {
                    if (this.enabled) console.warn(`%c[Permission][WARN] ${msg}`,
                        "color:#eab308;font-weight:bold;", data);
                },
                error(msg, data = null) {
                    if (this.enabled) console.error(`%c[Permission][ERROR] ${msg}`,
                        "color:#ef4444;font-weight:bold;", data);
                },
                debug(msg, data = null) {
                    if (this.enabled) console.log(`%c[Permission][DEBUG] ${msg}`,
                        "color:#16a34a;font-weight:bold;", data);
                }
            };

            // --- Helpers ---
            function syncRow(rowId) {
                const checkboxes = $(`.perm-checkbox[data-row="${rowId}"]`);
                const rowSelect = $(`.row-select[data-row="${rowId}"]`);
                const allChecked = checkboxes.length && checkboxes.toArray().every(cb => cb.checked);
                rowSelect.prop('checked', allChecked);
            }

            function syncColumnsAndGlobal() {
                ['view', 'insert', 'update', 'delete'].forEach(col => {
                    const colBoxes = $(`.${col}-checkbox`);
                    const header = $(`.column-select[data-column="${col}"]`);
                    const allChecked = colBoxes.length && colBoxes.toArray().every(cb => cb.checked);
                    header.prop('checked', allChecked);

                    LOG.debug(`Column synced → ${col}`, {
                        allChecked,
                        values: colBoxes.toArray().map(cb => cb.checked)
                    });
                });

                const allPerms = $('.perm-checkbox');
                const globalAll = allPerms.length && allPerms.toArray().every(cb => cb.checked);
                $('#select-all').prop('checked', globalAll);

                LOG.debug("Global select-all synced", {
                    globalAll
                });
            }

            // --- Row select toggles all ---
            $(document).on('change', '.row-select', function () {
                const rowId = this.dataset.row;
                const isChecked = this.checked;
                $(`.perm-checkbox[data-row="${rowId}"]`).prop('checked', isChecked);

                LOG.info(`Row-select toggled → ${rowId}`, {
                    isChecked
                });

                syncColumnsAndGlobal();
            });

            // --- Column select toggles all ---
            $(document).on('change', '.column-select', function () {
                const column = $(this).data('column');
                const isChecked = this.checked;
                $(`.${column}-checkbox`).prop('checked', isChecked).each(function () {
                    syncRow(this.dataset.row);
                });

                LOG.info(`Column-select toggled : ${column}`, {
                    isChecked
                });

                syncColumnsAndGlobal();
            });

            // --- Global select all ---
            $('#select-all').on('change', function () {
                const isChecked = this.checked;
                $('.perm-checkbox, .row-select, .column-select').prop('checked', isChecked);

                LOG.info("Global select-all toggled", {
                    isChecked
                });
            });

            // --- Auto-check view if insert/update/delete is checked ---
            $(document).on('change', '.insert-checkbox, .update-checkbox, .delete-checkbox', function () {
                const rowId = this.dataset.row;
                const viewCheckbox = $(`.view-checkbox[data-row="${rowId}"]`);
                const crudChecked = $(
                    `.insert-checkbox[data-row="${rowId}"], .update-checkbox[data-row="${rowId}"], .delete-checkbox[data-row="${rowId}"]`
                ).is(':checked');

                if (crudChecked && !viewCheckbox.prop('checked')) {
                    viewCheckbox.prop('checked', true);
                    LOG.info(`Auto-check VIEW → ${rowId}`);
                }

                LOG.debug(`CRUD toggled in row ${rowId}`, {
                    view: viewCheckbox.prop('checked'),
                    insert: $(`.insert-checkbox[data-row="${rowId}"]`).prop('checked'),
                    update: $(`.update-checkbox[data-row="${rowId}"]`).prop('checked'),
                    delete: $(`.delete-checkbox[data-row="${rowId}"]`).prop('checked'),
                });

                syncRow(rowId);
                syncColumnsAndGlobal();
            });

            // --- On page load ---
            $('[id^="row-"]').each(function () {
                syncRow(this.id);
            });
            syncColumnsAndGlobal();

            LOG.info("Permission edit page initialized");
        });
    </script>
@endpush