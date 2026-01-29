<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // app/Http/Controllers/ReportController.php
    // The main reports page
    public function index()
    {
        $products = Product::all();
        $movements = StockMovement::with(['product', 'user'])->latest()->paginate(10);

        return view('dashboard.report', compact('products', 'movements'));
    }

    // Your reportBroken function
    public function reportBroken(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255'
        ]);

        // 1. Create Movement Record
        StockMovement::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::user()->id,
            'quantity' => -$request->quantity,
            'type' => 'broken',
            'notes' => $request->notes
        ]);

        // 2. Update the Product's main quantity
        $product = Product::findOrFail($request->product_id);
        $product->decrement('qty', $request->quantity);

        return back()->with('success', 'Damage reported and stock updated.');
    }
    public function handleStock(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|not_in:0',
        'type' => 'required|in:in,transfer,broken,out',
    ]);

    // 1. Record movement
    StockMovement::create([
        'product_id' => $request->product_id,
        'user_id' => Auth::id(),
        'quantity' => $request->quantity, // Use negative for 'broken' or 'out' in the form
        'type' => $request->type,
        'notes' => $request->notes
    ]);

    // 2. Update Product Qty
    $product = Product::find($request->product_id);
    $product->increment('qty', $request->quantity); // Incrementing a negative number = subtraction

    return back()->with('success', 'Stock updated successfully.');
}
}
