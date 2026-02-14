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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pos', [PointOfSaleController::class, 'index'])->name('point_of_sale.index');
    Route::post('/pos/order', [PointOfSaleController::class, 'store'])->name('point_of_sale.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ADMIN ONLY - Management Tools
    Route::middleware(['can:admin-only'])->group(function () {
        // We removed the duplicate inventory here to keep it in the product section below
        Route::get('/employees', [DashboardController::class, 'employeeIndex'])->name('employees.index');
        Route::get('/admin/users', [AuthController::class, 'userControl'])->name('admin.users');
        Route::get('/admin/orders', [DashboardController::class, 'orders'])->name('orders.index');
    });

    // --- PRODUCT MANAGEMENT SECTION ---
    // Rule: Specific routes must come BEFORE resource routes
    
    // 1. Bulk Adjust (The "Save All" logic)
    Route::post('/products/bulk-adjust', [ProductController::class, 'bulkAdjustStock'])->name('products.bulk-adjust');

    // 2. The main inventory list
    Route::get('/inventory', [ProductController::class, 'index'])->name('inventory');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // 3. The Resource (Handles Create, Store, Edit, Update, Destroy)
    Route::resource('products', ProductController::class)->except(['index']);

    // --- SETTINGS & OTHERS ---
    Route::get('/settings', function () {
        return view('dashboard.settings.index', [
            'categories' => Category::withCount('products')->get(),
            'suppliers' => Supplier::all()
        ]);
    })->name('settings.index');

    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);

    // Employee Management
    Route::put('/employees/{id}', [DashboardController::class, 'employeeUpdate'])->name('employees.update');
    Route::delete('/employees/{id}', [DashboardController::class, 'employeeDestroy'])->name('employees.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/broken', [ReportController::class, 'reportBroken'])->name('reports.broken');
});