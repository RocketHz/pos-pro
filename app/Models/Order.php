<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'table_id',
        'user_id',
        'total',
        'status'
    ];

    // Relación: Una orden tiene muchos artículos (detalles)
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relación: Una orden pertenece a una mesa
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}