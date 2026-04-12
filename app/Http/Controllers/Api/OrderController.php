<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validación
        $validated = $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tip' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string|in:mobile_transfer,pos,cash,other',
            'payment_reference' => 'nullable|string|max:255',
            'payment_details' => 'nullable|string',
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                // 1. Crear la Orden
                $order = Order::create([
                    'table_id' => $validated['table_id'] ?? null,
                    'user_id' => Auth::id() ?? 1,
                    'total' => $validated['total'],
                    'discount' => $validated['discount'] ?? 0,
                    'tip' => $validated['tip'] ?? 0,
                    'payment_method' => $validated['payment_method'],
                    'payment_reference' => $validated['payment_reference'] ?? null,
                    'payment_details' => $validated['payment_details'] ?? null,
                    'status' => 'paid',
                ]);

                // 2. Si hay mesa asignada, marcarla como ocupada
                if ($validated['table_id']) {
                    Table::where('id', $validated['table_id'])
                        ->update(['status' => 'occupied']);
                }

                // 3. Procesar ítems y actualizar Stock
                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    // Validar stock suficiente
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stock insuficiente para: {$product->name}");
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);

                    // Decrementar stock
                    $product->decrement('stock', $item['quantity']);
                }

                return response()->json([
                    'message' => 'Venta registrada exitosamente',
                    'order_id' => $order->id,
                    'order' => $order->load('items.product')
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al procesar la venta',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $orders = Order::with(['items.product', 'table'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['orders' => $orders]);
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'table', 'user'])->findOrFail($id);

        return response()->json(['order' => $order]);
    }
}
