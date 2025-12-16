<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

// Task 1: Customer Form Routes
Route::get('/customer-form', [CustomerController::class, 'index'])->name('customer.form');
Route::post('/customer-form', [CustomerController::class, 'store'])->name('customer.store');

// ==================== ADMIN ROUTES (PROTECTED) ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    // Header Management
    Route::get('/header', [AdminController::class, 'header'])->name('admin.header');
    Route::post('/header/update', [AdminController::class, 'updateHeader'])->name('admin.header.update');

    // Banner Management
    Route::get('/banners', [AdminController::class, 'banners'])->name('admin.banners');
    Route::post('/banners', [AdminController::class, 'storeBanner'])->name('admin.banners.store');
    Route::put('/banners/{id}', [AdminController::class, 'updateBanner'])->name('admin.banners.update');
    Route::delete('/banners/{id}', [AdminController::class, 'destroyBanner'])->name('admin.banners.destroy');

    // Most Searched Cars
    Route::get('/most-searched-cars', [AdminController::class, 'mostSearchedCars'])->name('admin.most-searched-cars');
    Route::post('/most-searched-cars', [AdminController::class, 'storeMostSearchedCar'])->name('admin.most-searched-cars.store');
    Route::put('/most-searched-cars/{id}', [AdminController::class, 'updateMostSearchedCar'])->name('admin.most-searched-cars.update');
    Route::delete('/most-searched-cars/{id}', [AdminController::class, 'destroyMostSearchedCar'])->name('admin.most-searched-cars.destroy');

    // Latest Cars
    Route::get('/latest-cars', [AdminController::class, 'latestCars'])->name('admin.latest-cars');
    Route::post('/latest-cars', [AdminController::class, 'storeLatestCar'])->name('admin.latest-cars.store');
    Route::put('/latest-cars/{id}', [AdminController::class, 'updateLatestCar'])->name('admin.latest-cars.update');
    Route::delete('/latest-cars/{id}', [AdminController::class, 'destroyLatestCar'])->name('admin.latest-cars.destroy');

    // Customers
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::delete('/customers/{id}', [AdminController::class, 'destroyCustomer'])->name('admin.customers.destroy');

    // Footer
    Route::get('/footer', [AdminController::class, 'footer'])->name('admin.footer');
    Route::post('/footer/update', [AdminController::class, 'updateFooter'])->name('admin.footer.update');
});

Auth::routes(['register' => true]);