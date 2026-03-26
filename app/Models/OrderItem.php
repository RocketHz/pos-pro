<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    // Relación: Este ítem pertenece a una orden
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Relación: Este ítem está vinculado a un producto
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}