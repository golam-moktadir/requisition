@extends('layouts.app')

@section('title', '403 • Permission Denied')

@section('content')
    <section aria-labelledby="forbidden-title" class="min-h-[70vh] grid place-items-center px-4 py-10">
        <div
            class="w-full max-w-2xl rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700">

            <!-- Top accent -->
            <div class="h-1 w-full rounded-t-2xl bg-gradient-to-r from-red-500 to-rose-500"></div>

            <div class="flex flex-col items-center text-center gap-6 px-6 py-10 md:px-10">

                <!-- Icon + Title -->
                <div class="flex items-center gap-4">
                    <div class="rounded-2xl bg-red-100 p-4 shadow-sm dark:bg-red-900/30" aria-hidden="true">
                        <svg class="h-10 w-10 text-red-600 dark:text-red-400" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <h2 id="forbidden-title"
                            class="text-sm md:text-base font-medium uppercase tracking-wide text-red-600 dark:text-red-400">
                            Permission Denied
                        </h2>
                        <p class="mt-2 text-2xl md:text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Please <span class="text-red-600 dark:text-red-400 font-extrabold">contact your
                                administrator</span>
                            for access.
                        </p>
                    </div>
                </div>

                <!-- Details -->
                <dl class="space-y-2 text-base md:text-lg leading-relaxed text-gray-700 dark:text-gray-300">
                    <div class="flex flex-wrap justify-center gap-x-2">
                        <dt class="font-semibold">User:</dt>
                        <dd class="truncate max-w-[16rem] md:max-w-xs">
                            {{ $user->member_login ?? ($user->email ?? 'N/A') }}
                        </dd>
                    </div>
                    <div class="flex flex-wrap justify-center gap-x-2">
                        <dt class="font-semibold">Page:</dt>
                        <dd>{{ $page ?? 'Unknown' }}</dd>
                    </div>
                    <div class="flex flex-wrap justify-center gap-x-2">
                        <dt class="font-semibold">Required Ability:</dt>
                        <dd>{{ isset($ability) ? ucfirst($ability) : 'N/A' }}</dd>
                    </div>
                </dl>

                <!-- Message -->
                <p class="max-w-prose text-sm md:text-base text-gray-600 dark:text-gray-400">
                    🚫 You don’t have permission to access this page. If you believe this is a mistake, please contact your
                    administrator.
                </p>

                <!-- Actions -->
                <div class="mt-2 flex flex-wrap justify-center gap-3">
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm md:text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-gray-400 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus-visible:ring-gray-500 dark:focus-visible:ring-offset-gray-800 transition">
                        ← Go Back
                    </a>

                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center rounded-lg bg-red-600 px-5 py-2.5 text-sm md:text-base font-medium text-white shadow hover:bg-red-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-red-400 dark:bg-red-700 dark:hover:bg-red-600 dark:focus-visible:ring-red-500 dark:focus-visible:ring-offset-gray-800 transition">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
