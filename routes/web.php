<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\DoctorReportController;
use App\Http\Controllers\Admin\DoctorRoleController;
use App\Http\Controllers\Admin\InsuranceController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\OperationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SpecialityController;
use App\Http\Controllers\Admin\SurgeryController;
use App\Http\Middleware\CheckDoctorRoleSumForDashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Section Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::redirect('/', '/login');

// Authentication (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// Authenticated Admin Routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        // Auth
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware(CheckDoctorRoleSumForDashboard::class);

        // Resources
        Route::resources([
            'specialities' => SpecialityController::class,
            'doctor_roles' => DoctorRoleController::class,
            'doctors' => DoctorController::class,
            'insurances' => InsuranceController::class,
            'operations' => OperationController::class,
            'invoices' => InvoiceController::class,
        ]);

        // Surgeries with additional middleware
        Route::resource('surgeries', SurgeryController::class)
            ->middlewareFor(['create', 'store', 'edit', 'update'], [
                'doctor_role_sum',
                'check.doctor.unique',
            ]);

        // Invoices extra route
        Route::post('invoices/bulk-edit', [InvoiceController::class, 'bulkEdit'])
            ->name('invoices.bulk-edit');

        // Payments
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::post('store', [PaymentController::class, 'store'])->name('store');
            Route::delete('{payment}', [PaymentController::class, 'destroy'])->name('destroy');
            Route::patch('{payment}/status', [PaymentController::class, 'updateStatus'])->name('updateStatus');
        });

        // Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity_logs.index');

        // Doctor Report
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('doctor', [DoctorReportController::class, 'index'])->name('doctor.index');
            Route::get('doctor/show', [DoctorReportController::class, 'show'])->name('doctor.show');
        });

    });
