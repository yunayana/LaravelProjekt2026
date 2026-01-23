<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\MembershipController;
use App\Http\Controllers\Client\ClassController as ClientClassController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\ClientController;
use App\Http\Controllers\Employee\ClassController as EmployeeClassController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MembershipPlanController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\GymClassController;
use App\Http\Controllers\TrainerController;

// Strona powitalna (publiczna)
Route::get('/', [LandingController::class, 'index'])
    ->name('home');

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
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

        Route::get('/membership', [MembershipController::class, 'index'])->name('membership.index');
        Route::post('/membership', [MembershipController::class, 'store'])->name('membership.store');
        Route::delete('/membership', [MembershipController::class, 'cancel'])->name('membership.cancel');

        Route::get('/classes', [ClientClassController::class, 'index'])->name('classes.index');
        Route::post('/classes/{class}/register', [ClientClassController::class, 'register'])->name('classes.register');
        Route::delete('/classes/{class}/unregister', [ClientClassController::class, 'unregister'])->name('classes.unregister');

        Route::post('/membership/cancel-last-extension', [MembershipController::class, 'cancelLastExtension'])
            ->name('membership.cancel-last-extension');
    });

// Employee Routes (jeśli nadal potrzebne)
Route::middleware(['auth', 'verified', 'role:employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');

        Route::resource('clients', ClientController::class)->only(['index', 'show']);
        Route::resource('classes', EmployeeClassController::class);
    });

// Trainer Routes
Route::middleware(['auth', 'verified', 'role:trainer'])
    ->prefix('trainer')
    ->name('trainer.')
    ->group(function () {
        Route::get('/classes', [TrainerController::class, 'index'])->name('classes.index');
    });

// Admin Routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/trashed', [AdminUserController::class, 'trashed'])->name('users.trashed');
        Route::post('/users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{id}/force', [AdminUserController::class, 'forceDestroy'])->name('users.force-destroy');

        Route::resource('plans', MembershipPlanController::class)
            ->except(['show']);
        Route::resource('classes', GymClassController::class)->except(['show']);
    });

require __DIR__.'/auth.php';
