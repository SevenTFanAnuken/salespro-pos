<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use App\Models\Expense;
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
    $user = Auth::user();
    if ($user->role === 'user') {
        return redirect()->route('point_of_sale.index');
    }

    $totalSales = Sale::sum('total_amount');
    $totalExpenses = Expense::sum('amount');
    $netProfit = $totalSales - $totalExpenses;

    return view('dashboard.admin', [
        'activeUsers'  => User::count(),
        'totalProducts' => Product::count(),
        'totalSales' => $totalSales,
        'totalExpenses' => $totalExpenses,
        'netProfit' => $netProfit,
        'todaySales' => Sale::whereDate('created_at', today())->sum('total_amount')
    ]);
}



    public function posIndex()
    {
        return view('dashboard.pos.index');
    }
    public function posStore(Request $request)
{
    $request->validate([
        'cart_data' => 'required',
        'payment_type' => 'required'
    ]);

    $cartItems = json_decode($request->cart_data, true);

    DB::transaction(function () use ($cartItems, $request) {
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += (float)$item['price'] * (int)$item['qty'];
        }

        // 1. Create the Sale (Changed from Order to Sale)
        $sale = Sale::create([
            'user_id'         => Auth::id(),
            'invoice_number'  => 'INV-' . strtoupper(uniqid()), // Generates a unique invoice
            'total_amount'    => $totalAmount,
            'final_total'     => $totalAmount, // Usually total after tax/discount
            'payment_type'    => $request->payment_type,
        ]);

        // 2. Process each item
        foreach ($cartItems as $item) {
            // Save Sale Details (so your history page shows items!)
            $sale->details()->create([
                'product_id' => $item['id'],
                'qty'        => $item['qty'],
                'price'      => $item['price'],
                'subtotal'   => $item['price'] * $item['qty'],
            ]);

            // Log Stock Movement
            StockMovement::create([
                'product_id' => $item['id'],
                'user_id'    => Auth::id(),
                'quantity'   => -$item['qty'],
                'type'       => 'out',
                'notes'      => 'POS Sale ' . $sale->invoice_number
            ]);

            // Update Product Quantity
            Product::find($item['id'])->decrement('qty', $item['qty']);
        }
    });

    return back()->with('success', 'Sale recorded and revenue updated!');
}

    public function orders()
{
    // Fetch all orders with pagination so the page doesn't get too long
    $orders = Sale::with('user')->latest()->paginate(15);
    return view('dashboard.orders', compact('orders'));
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
