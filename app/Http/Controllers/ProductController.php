<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // 1. Fetch all products from the database
        $products = \App\Models\Product::all();

        // 2. Pass the variable to the view
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
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => 'required',
            'barcode' => 'required|unique:products,barcode,' . $product->id,
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'qty' => 'required|integer',
            'category_id' => 'required',
            'supplier_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle Image Replacement
        if ($request->hasFile('image')) {
            // 1. Delete the old image file if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // 2. Upload the new image
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
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

    // 2. Save the product to the database
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'barcode' => 'required|unique:products',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'qty' => 'required|integer',
            'category_id' => 'required',
            'supplier_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB Max
        ]);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Saves to storage/app/public/products
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        Product::create($data);

        return redirect()->route('products.index')
                     ->with('success', 'Great! The new product has been added to your inventory.');
    }
}
