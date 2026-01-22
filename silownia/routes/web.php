<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\MembershipController;
use App\Http\Controllers\Client\ClassController as ClientClassController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\ClientController;
use App\Http\Controllers\Employee\ClassController as EmployeeClassController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Client Routes
Route::middleware(['auth', 'verified', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])
            ->name('dashboard');

        // Karnety
        Route::get('/membership', [MembershipController::class, 'index'])
            ->name('membership.index');

        Route::post('/membership', [MembershipController::class, 'store'])
            ->name('membership.store');

        Route::delete('/membership', [MembershipController::class, 'cancel'])
            ->name('membership.cancel');

        // Zajęcia
        Route::get('/classes', [ClientClassController::class, 'index'])
            ->name('classes.index');

        Route::post('/classes/{class}/register', [ClientClassController::class, 'register'])
            ->name('classes.register');

        Route::post('/classes/{class}/unregister', [ClientClassController::class, 'unregister'])
            ->name('classes.unregister');
    });

// Employee Routes
Route::middleware(['auth', 'verified', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::resource('clients', ClientController::class)->only(['index', 'show']);
    Route::resource('classes', EmployeeClassController::class);
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
