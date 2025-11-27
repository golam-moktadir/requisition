<?php

use App\Http\Controllers\BusController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Departures\DepartureController;
use App\Http\Controllers\Departures\DepartureExceptionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OffenceController;
use App\Http\Controllers\OnlineBookingPolicyController;
use App\Http\Controllers\OperationalStaffController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PunishmentController;
use App\Http\Controllers\SalesReport\CitySalesController;
use App\Http\Controllers\SalesReport\CounterSalesReportController;
use App\Http\Controllers\SalesReport\EmployeeSalesController;
use App\Http\Controllers\SalesReport\ReportAjaxController;
use App\Http\Controllers\SalesReport\ScheduleSalesController;
use App\Http\Controllers\SubrouteTicketPriceController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| All dashboard routes organized by resource type with clear structure.
|
*/

Route::middleware(['auth:member'])->group(function () {

    Route::get('report', [DashboardController::class, 'showReportPage'])->name('report');

    /**
     * -------------------------------------
     * Employee Routes
     * URL Prefix: employees
     * Name Prefix: employees.
     * -------------------------------------
     */
    Route::prefix('employees')->name('employees.')->group(function () {
        // CRUD 
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
        // Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');

        // Additional Actions
        Route::get('/{employee}/profile', [EmployeeController::class, 'profile'])->name('profile');
        Route::put('/{employee}/status', [EmployeeController::class, 'updateStatus'])->name('toggle_status');
    });

    /**
     * -------------------------------------
     * Permission Routes
     * URL Prefix: permissions
     * Name Prefix: permissions.
     * -------------------------------------
     */
    Route::prefix('permissions')->name('permissions.')->group(function () {
        // Custom: current user's permissions as JSON
        Route::get('my-permissions', [PermissionController::class, 'getCurrentPermissionsJson'])
            ->name('myPermissions');

        // CRUD
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('create', [PermissionController::class, 'create'])->name('create');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::get('{permission}', [PermissionController::class, 'show'])->name('show');
        Route::get('{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::put('{permission}', [PermissionController::class, 'update'])->name('update');
        Route::delete('{permission}', [PermissionController::class, 'destroy'])->name('destroy');
    });

    /**
     * -------------------------------------
     * Online Booking Policy Routes
     * URL Prefix: booking-policies
     * Name Prefix: booking-policies.
     * -------------------------------------
     */
    Route::prefix('booking-policies')->name('booking-policies.')->group(function () {
        // CRUD routes
        Route::get('/', [OnlineBookingPolicyController::class, 'index'])->name('index');
        Route::post('/', [OnlineBookingPolicyController::class, 'store'])->name('store');

        // Commission routes
        Route::post('commissions', [OnlineBookingPolicyController::class, 'storeReturnTicketCommission'])
            ->name('commissions.store');
        Route::delete('commissions/{id}', [OnlineBookingPolicyController::class, 'destroyReturnCommission'])
            ->name('commissions.destroy');
    });

    /**
     * -------------------------------------
     * Departure Routes
     * URL Prefix: departures
     * Name Prefix: departures.
     * -------------------------------------
     */
    Route::prefix('departures')->name('departures.')->group(function () {
        // Basic CRUD
        Route::get('/', [DepartureController::class, 'index'])->name('index');
        Route::get('/create', [DepartureController::class, 'create'])->name('create');
        Route::post('/', [DepartureController::class, 'store'])->name('store');

        // Edit & update
        Route::get('/{cityDep}/{transport}/{route}/edit', [DepartureController::class, 'edit'])
            ->name('finalDeptDetails');
        Route::post('/update', [DepartureController::class, 'update'])->name('update');

        // Status & validity updates
        Route::post('/change-validity-date', [DepartureController::class, 'updateValidityDate'])
            ->name('updateValidityDate');
        Route::post('/change-online-status', [DepartureController::class, 'changeOnlineStatus'])
            ->name('changeOnlineStatus');
        Route::post('/update-block-status', [DepartureController::class, 'updateBlockStatus'])
            ->name('updateBlockStatus');
        Route::post('/bulk-block', [DepartureController::class, 'bulkBlock'])
            ->name('bulkBlock');

        // Setup & basic info
        Route::post('/submit-basic-info', [DepartureController::class, 'submitBasicInfo'])
            ->name('submitBasicInfo');
        Route::get('/setup/{transportId}/{routeId}', [DepartureController::class, 'setup'])
            ->name('setup');

    });

    /**
     * -------------------------------------
     * Departure Exception Routes
     * URL Prefix: exceptions
     * Name Prefix: exceptions.
     * -------------------------------------
     */
    Route::prefix('exceptions')->name('exceptions.')->group(function () {
        // Basic CRUD
        Route::get('/create', [DepartureExceptionController::class, 'create'])->name('create');
        Route::post('/', [DepartureExceptionController::class, 'store'])->name('store');

        // Setup & basic info
        Route::post('/submit-basic-info', [DepartureExceptionController::class, 'submitExceptionBasicInfo'])
            ->name('submitBasicInfo');
        Route::get('/setup/{transportId}/{routeId}', [DepartureExceptionController::class, 'setupException'])
            ->name('setup');
    });

    /**
     * -------------------------------------
     * Operational Staff Routes (e.g., drivers, guides, helpers).
     * URL Prefix: operational-staffs
     * Name Prefix: operational-staffs.
     * -------------------------------------
     */
    Route::prefix('operational-staffs')->name('operational-staffs.')->group(function () {

        // Driver Bus History Report 
        Route::prefix('/bus-history')
            ->name('bus-history.')
            ->group(function () {
                // Main page
                Route::get('/', [OperationalStaffController::class, 'driverBusHistoryReport'])
                    ->name('index');

                // Fetch single driver details (AJAX)
                Route::get('{id}/details', [OperationalStaffController::class, 'getDriverDetails'])
                    ->name('details');

                // Fetch driver bus history (AJAX with date filter)
                Route::get('{id}/history', [OperationalStaffController::class, 'getDriverBusHistory'])
                    ->name('history');
            });

        /**
         * Search operational staff by name or ID number
         * GET operational-staffs/search
         * Returns JSON for autocomplete or search suggestions
         */
        Route::get('search', [OperationalStaffController::class, 'search'])
            ->name('search');

        // CRUD routes
        Route::get('/', [OperationalStaffController::class, 'index'])
            ->name('index');
        Route::get('/create', [OperationalStaffController::class, 'create'])
            ->name('create');
        Route::post('/', [OperationalStaffController::class, 'store'])
            ->name('store');
        Route::get('{operationalStaff}', [OperationalStaffController::class, 'show'])
            ->name('show');
        Route::get('{operationalStaff}/edit', [OperationalStaffController::class, 'edit'])
            ->name('edit');
        Route::put('{operationalStaff}', [OperationalStaffController::class, 'update'])
            ->name('update');

        // Additional Actions
        // Toggle staff status (active/inactive)
        Route::patch('{operationalStaff}/toggle-status', [OperationalStaffController::class, 'toggleStatus'])
            ->name('toggle-status');

        Route::put('{operationalStaff}/status', [OperationalStaffController::class, 'updateStatus'])
            ->name('status.update');

        // Generate PDF for a staff record. Returns a downloadable PDF with staff details
        // Route::get('{operationalStaff}/pdf', [OperationalStaffController::class, 'pdf'])
        //     ->name('pdf');

        // Assign Vehicle 
        Route::get('{id}/assign-vehicle', [OperationalStaffController::class, 'assignVehicleForm'])->name('assign-vehicle');

        Route::post(
            '{id}/assign-vehicle',
            [OperationalStaffController::class, 'assignVehicleStore']
        )->name('assign-vehicle.store');

        Route::post('assign-vehicle/{id}/update', [OperationalStaffController::class, 'updateVehicleAssignment'])->name('assign-vehicle.update');
    });

    /**
     * -------------------------------------
     * Offence Routes
     * URL Prefix: offences
     * Name Prefix: offences.
     * -------------------------------------
     */
    Route::prefix('offences')->name('offences.')->group(function () {
        // CRUD routes
        Route::get('/', [OffenceController::class, 'index'])->name('index');
        Route::get('create', [OffenceController::class, 'create'])->name('create');
        Route::post('/', [OffenceController::class, 'store'])->name('store');
        Route::get('{offence}', [OffenceController::class, 'show'])->name('show');
        Route::get('{offence}/edit', [OffenceController::class, 'edit'])->name('edit');
        Route::put('{offence}', [OffenceController::class, 'update'])->name('update');
        Route::delete('{offence}', [OffenceController::class, 'destroy'])->name('destroy');

        // Additional actions
        Route::delete('{offence}/force-delete', [OffenceController::class, 'forceDelete'])
            ->name('forceDelete');
        Route::put('{offence}/status', [OffenceController::class, 'updateStatus'])
            ->name('status.update');

        // Offence attachments
        Route::delete('{offence}/attachments/{attachment}', [OffenceController::class, 'attachmentDelete'])
            ->name('attachmentDelete');
    });

    /**
     * -------------------------------------
     * Punishment Routes
     * URL Prefix: punishments
     * Name Prefix: punishments.
     * -------------------------------------
     */
    Route::prefix('punishments')->name('punishments.')->group(function () {
        // CRUD routes
        Route::get('/', [PunishmentController::class, 'index'])->name('index');
        Route::get('create', [PunishmentController::class, 'create'])->name('create');
        Route::post('/', [PunishmentController::class, 'store'])->name('store');
        Route::get('{punishment}', [PunishmentController::class, 'show'])->name('show');
        Route::get('{punishment}/edit', [PunishmentController::class, 'edit'])->name('edit');
        Route::put('{punishment}', [PunishmentController::class, 'update'])->name('update');

        // Additional actions
        Route::get('offences/{staffId}', [PunishmentController::class, 'getOffencesByStaff'])
            ->name('offences');
    });

    /**
     * -------------------------------------
     * Bus Routes
     * URL Prefix: buses
     * Name Prefix: buses.
     * -------------------------------------
     */
    Route::prefix('buses')->name('buses.')->group(function () {
        // CRUD routes
        Route::get('/', [BusController::class, 'index'])->name('index');
        Route::get('create', [BusController::class, 'create'])->name('create');
        Route::post('/', [BusController::class, 'store'])->name('store');
        Route::get('{bus}', [BusController::class, 'show'])->name('show');
        Route::get('{bus}/edit', [BusController::class, 'edit'])->name('edit');
        Route::put('{bus}', [BusController::class, 'update'])->name('update');
    });

    /**
     * -------------------------------------
     * Vehicle Routes
     * URL Prefix: vehicles
     * Name Prefix: vehicles.
     * -------------------------------------
     */
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        // CRUD routes
        Route::get('/', [VehicleController::class, 'index'])->name('index');
        Route::get('create', [VehicleController::class, 'create'])->name('create');
        Route::post('/', [VehicleController::class, 'store'])->name('store');
        Route::get('{vehicle}', [VehicleController::class, 'show'])->name('show');
        Route::get('{vehicle}/edit', [VehicleController::class, 'edit'])->name('edit');
        Route::put('{vehicle}', [VehicleController::class, 'update'])->name('update');

        // Export route
        Route::post('export', [VehicleController::class, 'export'])->name('export');
    });

    /**
     * -------------------------------------
     * Sales-report Routes
     * URL Prefix: sales-report
     * Name Prefix: sales-report.
     * -------------------------------------
     */
    Route::prefix('sales-report')->name('sales-report.')->group(function () {
        // ----- Global Sales Report Routes --------
        // Common AJAX endpoints for all reports (filter menus)
        Route::post('/preview/{key}', [ReportAjaxController::class, 'preview'])->name('preview');

        Route::get('ajax/booths/{cityId}', [ReportAjaxController::class, 'booths'])
            ->name('ajax.booths');
        Route::get('ajax/schedules/{routeId}', [ReportAjaxController::class, 'schedules'])
            ->name('ajax.schedules');

        // Individual Report Routes
        // Counter-wise 
        Route::get('counter-wise', [CounterSalesReportController::class, 'index'])->name('counter-wise');
        Route::post('counter-wise/data', [CounterSalesReportController::class, 'data'])
            ->name('counter-wise.data');

        // City-wise 
        Route::get('city-wise', [CitySalesController::class, 'index'])->name('city-wise');
        Route::post('city-wise/data', [CitySalesController::class, 'data'])
            ->name('city-wise.data');

        // Schedule-wise 
        Route::get('schedule-wise', [ScheduleSalesController::class, 'index'])->name('schedule-wise');
        Route::post('schedule-wise/data', [ScheduleSalesController::class, 'data'])
            ->name('schedule-wise.data');

        // Employee-wise
        Route::get('employee-wise', [EmployeeSalesController::class, 'index'])->name('employee-wise');
        Route::post('employee-wise/data', [EmployeeSalesController::class, 'data'])
            ->name('employee-wise.data');

    });

    /**
     * -------------------------------------
     * Subroute Ticket Price Routes
     * URL Prefix : ticket-price
     * Name Prefix: ticket-price.
     * Controller : SubrouteTicketPriceController
     * -------------------------------------
     */
    Route::prefix('ticket-price')
        ->name('ticket-price.')
        ->controller(SubrouteTicketPriceController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index'); // route selection form
            Route::get('/create', 'create')->name('create'); // ticket price setup form
            Route::post('/', 'store')->name('store'); // store new or updated ticket prices
            Route::get('/{groupId}/edit', 'edit')->name('edit'); // edit existing ticket price
            Route::put('/{groupId}', 'update')->name('update'); // update existing record
    
            Route::get('/routes-with-return/{transportId}', 'getRoutesWithReturn')
                ->name('routes-with-return');

            // Fetch subroute details (AJAX)
            Route::post('/subroute-details', 'subrouteDetails')->name('subrouteDetails');
        });

});
