<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['user_id', 'invoice_number', 'total_amount', 'final_total', 'payment_type'];

    public function details() {
        return $this->hasMany(SaleDetail::class);
    }
}
