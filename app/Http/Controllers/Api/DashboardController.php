<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics and chart data
     */
    public function index()
    {
        $today = now()->startOfDay();

        // Stats cards
        $salesToday = Order::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->sum('total');

        $transactions = Order::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->count();

        $avgTicket = $transactions > 0 ? $salesToday / $transactions : 0;

        $tipsToday = Order::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->sum('tip');

        $activeTables = Table::where('status', 'occupied')->count();

        // Chart: Hourly sales (last 12 hours)
        $hourlySales = Order::where('status', 'paid')
            ->where('created_at', '>=', now()->subHours(12))
            ->selectRaw('EXTRACT(HOUR FROM created_at) as hour, SUM(total) as total')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('total', 'hour')
            ->toArray();

        // Fill missing hours with 0
        $hourlyChartData = [];
        for ($i = 0; $i < 12; $i++) {
            $hour = (now()->hour - 11 + $i + 24) % 24;
            $hourlyChartData[] = $hourlySales[$hour] ?? 0;
        }

        // Chart: Top selling products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($query) {
                $query->where('status', 'paid')
                    ->whereDate('created_at', $today);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name ?? 'Producto eliminado',
                    'total' => $item->total_sold
                ];
            });

        // If no data, provide placeholder data
        if ($topProducts->isEmpty()) {
            $topProducts = collect([
                ['name' => 'Sin datos', 'total' => 0]
            ]);
        }

        return response()->json([
            'stats' => [
                'salesToday' => (float) $salesToday,
                'transactions' => $transactions,
                'avgTicket' => (float) $avgTicket,
                'tips' => (float) $tipsToday,
                'activeTables' => $activeTables,
            ],
            'charts' => [
                'hourlySales' => $hourlyChartData,
                'topProducts' => $topProducts,
            ]
        ]);
    }

    /**
     * Get weekly sales report
     */
    public function weeklySales()
    {
        $weeklySales = Order::where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Fill missing days
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('D');
            $data[] = $weeklySales[$date] ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }

    /**
     * Get payment methods breakdown
     */
    public function paymentMethods()
    {
        $today = now()->startOfDay();

        $total = Order::where('status', 'paid')
            ->whereDate('created_at', $today)
            ->count();

        if ($total === 0) {
            return response()->json([
                'methods' => [
                    ['label' => 'Efectivo', 'key' => 'cash', 'count' => 0, 'percentage' => 0, 'icon' => 'fas fa-money-bill-wave', 'color' => 'text-emerald-400'],
                    ['label' => 'Punto de Venta', 'key' => 'pos', 'count' => 0, 'percentage' => 0, 'icon' => 'fas fa-credit-card', 'color' => 'text-blue-400'],
                    ['label' => 'Pago Móvil', 'key' => 'mobile_transfer', 'count' => 0, 'percentage' => 0, 'icon' => 'fas fa-mobile-alt', 'color' => 'text-purple-400'],
                    ['label' => 'Otro', 'key' => 'other', 'count' => 0, 'percentage' => 0, 'icon' => 'fas fa-ellipsis-h', 'color' => 'text-amber-400'],
                ]
            ]);
        }

        $methods = Order::where('status', 'paid')
            ->whereDate('created_at', $today)
            ->selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method')
            ->toArray();

        $labels = [
            'cash' => 'Efectivo',
            'pos' => 'Punto de Venta',
            'mobile_transfer' => 'Pago Móvil/Transferencia',
            'other' => 'Otro',
        ];

        $icons = [
            'cash' => 'fas fa-money-bill-wave',
            'pos' => 'fas fa-credit-card',
            'mobile_transfer' => 'fas fa-mobile-alt',
            'other' => 'fas fa-ellipsis-h',
        ];

        $colors = [
            'cash' => 'text-emerald-400',
            'pos' => 'text-blue-400',
            'mobile_transfer' => 'text-purple-400',
            'other' => 'text-amber-400',
        ];

        $result = [];
        foreach (['cash', 'pos', 'mobile_transfer', 'other'] as $key) {
            $count = $methods[$key] ?? 0;
            $result[] = [
                'label' => $labels[$key],
                'key' => $key,
                'count' => $count,
                'percentage' => round(($count / $total) * 100),
                'icon' => $icons[$key],
                'color' => $colors[$key],
            ];
        }

        return response()->json(['methods' => $result]);
    }

    /**
     * Get recent transactions
     */
    public function recentTransactions()
    {
        $paymentLabels = [
            'cash' => 'Efectivo',
            'pos' => 'P. de Venta',
            'mobile_transfer' => 'Pago Móvil',
            'other' => 'Otro',
        ];

        $orders = Order::with(['items.product', 'table'])
            ->where('status', 'paid')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($order) use ($paymentLabels) {
                return [
                    'id' => $order->id,
                    'date' => $order->created_at->format('d/m/Y H:i'),
                    'table' => $order->table ? "Mesa {$order->table->number}" : 'Sin mesa',
                    'items' => $order->items->count(),
                    'total' => number_format($order->total, 2),
                    'status' => $order->status,
                    'payment_method' => $paymentLabels[$order->payment_method] ?? 'Otro',
                    'payment_reference' => $order->payment_reference,
                ];
            });

        return response()->json(['orders' => $orders]);
    }
}
