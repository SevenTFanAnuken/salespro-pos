<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PointOfSaleController extends Controller
{
    /**
     * Show the POS screen with only in-stock products.
     */
    public function index()
    {
        // Fetch products that have at least 1 in stock
        $products = Product::where('qty', '>', 0)->get();
        
        return view('dashboard.point_of_sale', compact('products')); 
    }

    /**
     * Process the sale and update inventory using a Transaction for safety.
     */
    // Inside PointOfSaleController.php

public function store(Request $request)
{
    $cartData = json_decode($request->cart_data, true);

    if (empty($cartData)) {
        return back()->with('error', 'Cart is empty');
    }

    DB::beginTransaction();

    try {
        $sale = new Sale();
        $sale->user_id = Auth::id();
        $sale->invoice_number = 'INV-' . date('YmdHis') . '-' . rand(10, 99);
        
        // Ensure this matches the column name in your 'sales' table
        $total = collect($cartData)->sum(fn($item) => $item['price'] * $item['qty']);
        $sale->total_amount = $total;
        $sale->final_total = $total; 
        $sale->payment_type = $request->payment_type ?? 'Cash';
        $sale->save();

        foreach ($cartData as $item) {
            $product = Product::findOrFail($item['id']);

            if ($product->qty < $item['qty']) {
                throw new \Exception("Insufficient stock for: " . $product->name);
            }

            SaleDetail::create([
                'sale_id'    => $sale->id,
                'product_id' => $item['id'],
                'qty'        => $item['qty'],
                'price'      => $item['price'],
                'subtotal'   => $item['price'] * $item['qty'],
            ]);

            $product->decrement('qty', $item['qty']);
        }

        DB::commit();
        
        // This 'success' key triggers the popup we added to the frontend
        return redirect()->route('point_of_sale.index')->with('success', 'Sale #' . $sale->invoice_number . ' completed!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed: ' . $e->getMessage());
    }
}
    
}