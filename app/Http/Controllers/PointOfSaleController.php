<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Auth;

class PointOfSaleController extends Controller
{
    /**
     * Show the POS screen with all available products.
     */
    public function index()
{
    $products = Product::where('qty', '>', 0)->get();
    // Point this to the file that HAS the @section('content')
    return view('dashboard.point_of_sale', compact('products')); 
}

    /**
     * Process the sale and update inventory.
     */
    public function store(Request $request)
{
    // 1. Decode the JSON cart data from your hidden input
    $cartData = json_decode($request->cart_data, true);

    if (empty($cartData)) {
        return back()->with('error', 'Cart is empty');
    }

    // 2. Create the main Sale record
    $sale = new Sale();
    $sale->user_id = auth::user()->id;
    $sale->invoice_number = 'INV-' . date('YmdHis');
    $sale->total_amount = collect($cartData)->sum(fn($item) => $item['price'] * $item['qty']);
    $sale->final_total = $sale->total_amount; // Add tax/discount logic here if needed
    $sale->payment_type = $request->payment_type ?? 'Cash';
    $sale->save();

    // 3. Create Sale Details & Update Product Stock
    foreach ($cartData as $item) {
        SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $item['id'],
            'qty' => $item['qty'],
            'price' => $item['price'],
            'subtotal' => $item['price'] * $item['qty'],
        ]);

        // Deduct stock from products table
        $product = Product::find($item['id']);
        $product->decrement('qty', $item['qty']);
    }

    return redirect()->route('point_of_sale.index')->with('success', 'Sale completed: ' . $sale->invoice_number);
}
}
