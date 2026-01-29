<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\StockMovement;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth::user();

        if ($user->role === 'user') {
            return redirect()->route('point_of_sale.index');
        }

        // 1. Calculate Revenue (assuming 'total_amount' column in orders table)
        $totalRevenue = \App\Models\Order::sum('total_amount');

        // 2. Count Active Users (all registered staff)
        $activeUsers = \App\Models\User::count();

        // 3. Get Recent Sales
        $recentSales = \App\Models\Order::latest()->take(5)->get();

        return view('dashboard.admin', compact('totalRevenue', 'activeUsers', 'recentSales'));
    }

    public function posIndex()
    {
        return view('dashboard.pos.index');
    }
    public function posStore(Request $request)
    {
        // 1. Validate the incoming request
        $request->validate([
            'cart_data' => 'required',
            'payment_type' => 'required'
        ]);

        $cartItems = json_decode($request->cart_data, true);

        if (empty($cartItems)) {
            return back()->with('error', 'Your cart is empty!');
        }

        // Use a Database Transaction to ensure everything saves together
        DB::transaction(function () use ($cartItems, $request) {

            // 2. Calculate Total
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item['price'] * $item['qty'];
            }

            // 3. Create the Main Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'paid_amount' => $totalAmount, // Assuming exact pay for now
                'change_amount' => 0,
                'payment_method' => $request->payment_type,
            ]);

            // 4. Process each item (Update Stock & Log Movement)
            foreach ($cartItems as $item) {
                // Log the "Stock Out" for Reports
                StockMovement::create([
                    'product_id' => $item['id'],
                    'user_id' => Auth::id(),
                    'quantity' => -$item['qty'], // Negative for stock leaving
                    'type' => 'out',
                    'notes' => 'POS Sale #' . $order->id
                ]);

                // Subtract from Product Quantity
                $product = Product::find($item['id']);
                $product->decrement('qty', $item['qty']);
            }
        });

        return back()->with('success', 'Sale recorded and stock updated!');
    }

    public function orders()
    {
        // Logic for orders page
        return view('dashboard.orders');
    }

    public function inventory()
    {
        $products = \App\Models\Product::all();
        return view('dashboard.inventory', compact('products'));
    }

    // Employee List
    public function employeeIndex()
    {
        // Fetch all registered users
        $users = \App\Models\User::all();
        return view('dashboard.admin.employees', compact('users'));
    }

    public function employeeUpdate(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->update($request->validate([
            'role' => 'required|in:admin,user'
        ]));

        return back()->with('success', 'Employee role updated!');
    }

    // Supplier List
    public function supplierIndex()
    {
        return view('dashboard.admin.suppliers');
    }

    // Attendance Check-in
    public function attendanceIndex()
    {
        // Using Auth::id() usually stops the red underline in VS Code
        $userId = Auth::id();

        $myLogs = Attendance::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.attendance', compact('myLogs'));
    }
}
