<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = ['user_id', 'total_amount', 'paid_amount', 'change_amount', 'payment_method'];

    // This links the order to the User (Cashier)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
