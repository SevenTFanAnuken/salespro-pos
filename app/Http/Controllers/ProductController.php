<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        // Ensure this points to your file in resources/views/dashboard/inventory.blade.php
        return view('dashboard.inventory', compact('products'));
    }

    // 1. Show the edit form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('dashboard.products.edit', compact('product', 'categories', 'suppliers'));
    }

    // 2. Process the update
    // Use this for the update method
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate
        $data = $request->validate([
            'name' => 'required',
            'barcode' => 'required|unique:products,barcode,' . $product->id,
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'qty' => 'required|integer',
            'category_id' => 'required',
            'supplier_id' => 'required',
        ]);

        // Update basic info
        $product->update($data);

        // Only record expense if quantity actually increased via the edit form
        if ($request->qty > $product->getOriginal('qty')) {
            $added = $request->qty - $product->getOriginal('qty');
            Expense::create([
                'description' => "Stock Adjustment: " . $product->name,
                'amount' => $added * $product->cost_price,
                'category' => 'Stock Purchase'
            ]);
        }

        return redirect()->route('inventory')->with('success', 'Product updated successfully!');
    }
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Product deleted successfully!');
    }

    // 1. Show the form to add a product
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('dashboard.products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
{
    // 1. Check if we even reach the method
    // dd($request->all()); 

    $data = $request->validate([
        'name' => 'required',
        'barcode' => 'required|unique:products',
        'cost_price' => 'required|numeric',
        'sale_price' => 'required|numeric',
        'qty' => 'required|integer',
        'category_id' => 'required',
        'supplier_id' => 'required',
        'image' => 'nullable|image|max:2048',
    ]);

    DB::beginTransaction();
    try {
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        // Record the Expense
        Expense::create([
            'description' => "Initial Stock: " . $product->name,
            'amount' => $request->cost_price * $request->qty,
            'category' => 'Stock Purchase'
        ]);

        DB::commit();
        return redirect()->route('inventory')->with('success', 'Product added!');

    } catch (\Exception $e) {
        DB::rollBack();
        // This will tell you exactly what went wrong (e.g., missing column in DB)
        return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
    }
}
    public function bulkAdjustStock(Request $request)
    {
        $adjustments = $request->input('adjustments', []);

        // DEBUG: This will stop the app and show you the data. 
        // If you don't see this when you click save, your form is the problem.
        // dd($adjustments); 

        DB::beginTransaction();
        try {
            foreach ($adjustments as $id => $change) {
                $change = (int)$change;
                if ($change == 0) continue;

                $product = Product::find($id); // Changed from findOrFail to prevent crashing
                if (!$product) continue;

                if ($change > 0) {
                    Expense::create([
                        'description' => "Bulk Restock: " . $product->name . " (+$change)",
                        'amount' => $product->cost_price * $change,
                        'category' => 'Stock Purchase'
                    ]);
                }

                // Using direct update instead of increment to ensure it hits the DB
                $product->qty = $product->qty + $change;
                $product->save();
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Inventory updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
}
