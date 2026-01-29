<?php

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PointOfSaleController;
use App\Http\Controllers\ReportController;

// Add this at the top of web.php
Route::get('/', function () {
    return redirect()->route('login');
});

// Public Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated Dashboard Routes
Route::middleware('auth')->group(function () {

    // 1. The main dashboard URL (Where Laravel tries to go after login)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pos', [PointOfSaleController::class, 'index'])->name('point_of_sale.index');
    Route::post('/pos/order', [PointOfSaleController::class, 'store'])->name('point_of_sale.store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ADMIN ONLY - Management Tools
    Route::middleware(['can:admin-only'])->group(function () {
        Route::get('/inventory', [DashboardController::class, 'inventory'])->name('inventory');
        Route::get('/employees', [DashboardController::class, 'employeeIndex'])->name('employees.index');
        Route::get('/suppliers', [DashboardController::class, 'supplierIndex'])->name('suppliers.index');
        Route::get('/admin/users', [AuthController::class, 'userControl'])->name('admin.users');
    });

    // Page to see the form
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

    // Action to save the data
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');

    Route::resource('products', ProductController::class);
    // If you are visiting /inventory, make sure it hits the Controller 'index'
    Route::get('/inventory', [ProductController::class, 'index'])->name('inventory');

    // Page to view them
    Route::get('/settings', function () {
        return view('dashboard.settings.index', [
            'categories' => Category::withCount('products')->get(),
            'suppliers' => Supplier::all()
        ]);
    })->name('settings.index');

    // Actions to save them
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);

    // Employee Management Routes
    Route::get('/employees', [DashboardController::class, 'employeeIndex'])->name('employees.index');
    Route::put('/employees/{id}', [DashboardController::class, 'employeeUpdate'])->name('employees.update');
    Route::delete('/employees/{id}', [DashboardController::class, 'employeeDestroy'])->name('employees.destroy');

    // Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/broken', [ReportController::class, 'reportBroken'])->name('reports.broken');
});
