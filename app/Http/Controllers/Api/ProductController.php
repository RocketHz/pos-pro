<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Retornamos categorías con sus productos cargados (Eager Loading)
        return response()->json([
            'categories' => Category::all(),
            'products' => Product::where('is_active', true)->get()
        ]);
    }
}