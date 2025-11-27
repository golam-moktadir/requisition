@extends('layouts.app')

@section('title', 'Permission Overview')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/permissions/permission-create-edit.css') }}" as="style">
@endpush

@section('content')
    <div class="container mx-auto p-2">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">

            @if(session('success'))
                <div id="toast-success" class="fixed top-4 right-4 z-50 flex items-start gap-3 w-80 max-w-full rounded-lg shadow-lg
                                        bg-green-600 text-white text-sm px-4 py-3">

                    <!-- Icon -->
                    <div class="flex-shrink-0 mt-0.5">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>

                    <!-- Message -->
                    <div class="flex-1">
                        <p class="font-semibold"> {{ session('success.msg') }} </p>

                        @if(!empty(session('success.changes')))
                            <ul class="list-disc list-inside mt-1 space-y-0.5 text-xs text-green-100">
                                @foreach(session('success.changes') as $c)
                                    <li>{{ $c }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Close button -->
                    <button onclick="document.getElementById('toast-success').remove()"
                        class="ml-2 text-white/80 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Header -->
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <!-- Title & Context -->
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center flex-wrap gap-2">
                        <i class="fas fa-user-shield text-blue-600"></i>
                        <span>Permission Overview</span>

                        <!-- Employee -->
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            {{ $result['employee']->employee_name }} ({{ $result['employee']->member_login }})
                        </span>

                        <!-- Count -->
                        <span
                            class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                            {{ $result['employee']->permission_count }} total
                        </span>
                    </h1>

                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('permissions.edit', \App\Helpers\ShortEncryptor::encrypt($result['employee']->employee_id)) }}"
                            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-white rounded-md shadow-sm transition-colors bg-green-600 hover:bg-green-700 dark:hover:bg-green-500">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('permissions.index') }}"
                            class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-md shadow-sm transition-colors bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Employee / Transport Info -->
            <div class="grid grid-cols-[30%_minmax(0,_1fr)] text-xs border-b border-white">
                <div
                    class="py-1 px-2 border-r border-b border-white flex items-center justify-end font-semibold bg-[#999999] text-white">
                    Transport Name
                </div>
                <div class="py-1 px-2 border-b border-white flex items-center bg-[#CCCCCC]">
                    <span class="text-black font-bold">
                        {{ $result['employee']->transport_name ?? 'N/A' }}
                    </span>
                </div>
                <div
                    class="py-1 px-2 border-r border-white flex items-center justify-end font-semibold bg-[#999999] text-white">
                    Employee Name
                </div>
                <div class="py-1 px-2 border-b border-white flex items-center bg-[#CCCCCC]">
                    <span class="text-black font-bold">
                        {{ $result['employee']->employee_name }} ({{ $result['employee']->member_login }})
                    </span>
                </div>
            </div>

            <!-- Permissions Table -->
            <div class="table-responsive p-2">
                <h2 class="text-base font-semibold text-gray-800 dark:text-white mb-2 flex items-center gap-2">
                    <i class="fas fa-list text-blue-600"></i>
                    <span>Permission List</span>
                </h2>

                <table class="table table-bordered table-striped align-middle text-center w-full text-xs">
                    <thead>
                        <tr>
                            <th class="bg-legacy-blue text-white border border-white py-1 px-2">#</th>
                            <th class="bg-legacy-blue text-white border border-white py-1 px-2 text-left">Page Title</th>
                            <th class="bg-legacy-blue-soft text-white border border-white py-1 px-2">View</th>
                            <th class="bg-legacy-blue-soft text-white border border-white py-1 px-2">Insert</th>
                            <th class="bg-legacy-blue-soft text-white border border-white py-1 px-2">Update</th>
                            <th class="bg-legacy-blue-soft text-white border border-white py-1 px-2">Delete</th>
                            <th class="bg-legacy-blue text-white border border-white py-1 px-2">All</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($result['permissions'] as $index => $perm)
                                        @php
                                            $allChecked =
                                                $perm->permission_view &&
                                                $perm->permission_insert &&
                                                $perm->permission_update &&
                                                $perm->permission_delete;
                                        @endphp
                                        <tr class="hover:bg-blue-50 transition duration-150 ease-in-out">
                                            <td class="border border-gray-200 py-2 px-3 text-gray-600 text-sm">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="border border-gray-200 py-2 px-3 text-left font-semibold text-gray-900">
                                                {{ $perm->page_title ?? '-' }}
                                            </td>
                                            <td class="border border-gray-200 py-2 px-3 text-center">
                                                {!! $perm->permission_view
                            ? '<span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-600 rounded-full"><i class="fas fa-check"></i></span>'
                            : '<span class="text-gray-400">—</span>' !!}
                                            </td>
                                            <td class="border border-gray-200 py-2 px-3 text-center">
                                                {!! $perm->permission_insert
                            ? '<span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-600 rounded-full"><i class="fas fa-check"></i></span>'
                            : '<span class="text-gray-400">—</span>' !!}
                                            </td>
                                            <td class="border border-gray-200 py-2 px-3 text-center">
                                                {!! $perm->permission_update
                            ? '<span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-600 rounded-full"><i class="fas fa-check"></i></span>'
                            : '<span class="text-gray-400">—</span>' !!}
                                            </td>
                                            <td class="border border-gray-200 py-2 px-3 text-center">
                                                {!! $perm->permission_delete
                            ? '<span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-600 rounded-full"><i class="fas fa-check"></i></span>'
                            : '<span class="text-gray-400">—</span>' !!}
                                            </td>
                                            <td class="border border-gray-200 py-2 px-3 text-center">
                                                {!! $allChecked
                            ? '<span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold bg-green-200 text-green-800 rounded-md">All</span>'
                            : '<span class="text-gray-400">—</span>' !!}
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500 italic">
                                    No permissions assigned.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if (toast) {
                toast.style.transition = "opacity 0.5s ease";
                toast.style.opacity = "0";
                setTimeout(() => toast.remove(), 500);
            }
        }, 5000);
    </script>
@endpush