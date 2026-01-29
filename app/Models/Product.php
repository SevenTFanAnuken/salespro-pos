<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'barcode',
        'cost_price',
        'sale_price',
        'qty',
        'image'
    ];

    // Get the Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Get the Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Profit Calculation Helper
    public function getProfitAttribute()
    {
        return $this->sale_price - $this->cost_price;
    }
    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
