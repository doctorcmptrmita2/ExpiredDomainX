<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WatchlistController;
use App\Http\Middleware\EnsurePlanAllowed;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/domains', [DomainController::class, 'index'])->name('domains.index');
    Route::get('/domains/{domain}', [DomainController::class, 'show'])->name('domains.show');
    Route::get('/domains/export/csv', [DomainController::class, 'export'])
        ->middleware(EnsurePlanAllowed::class . ':pro')
        ->name('domains.export');

    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/{domain}', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::delete('/watchlist/{domain}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');

    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/upgrade', [BillingController::class, 'upgrade'])->name('billing.upgrade');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/plan', [AdminUserController::class, 'updatePlan'])->name('users.update-plan');
});

require __DIR__.'/auth.php';
