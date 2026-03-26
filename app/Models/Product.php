<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    // Campos que permitimos llenar desde formularios
    protected $fillable = [
        'category_id', 
        'name', 
        'price', 
        'stock', 
        'image', 
        'is_active'
    ];

    // Relación: El producto pertenece a una categoría
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}