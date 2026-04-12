<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /**
     * Validate a coupon code
     */
    public function check(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = strtoupper(trim($validated['code']));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Cupón inválido'
            ], 404);
        }

        $validation = $coupon->isValid($validated['subtotal']);

        if (!$validation['valid']) {
            return response()->json($validation, 400);
        }

        // Check per-customer limit
        if ($coupon->usage_limit_per_customer) {
            $userUsage = DB::table('coupon_usage')
                ->where('coupon_id', $coupon->id);

            if (Auth::check()) {
                $userUsage->where('user_id', Auth::id());
            } else {
                $userUsage->where('customer_identifier', $request->ip());
            }

            $customerCount = $userUsage->count();

            if ($customerCount >= $coupon->usage_limit_per_customer) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Has alcanzado el límite de uso para este cupón'
                ], 400);
            }
        }

        $discount = $coupon->calculateDiscount($validated['subtotal']);

        // Track usage when validated (POS flow: validate -> checkout immediately)
        self::trackUsage($coupon->id);

        return response()->json([
            'valid' => true,
            'message' => '¡Cupón aplicado!',
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => $discount
        ]);
    }

    /**
     * Track coupon usage for an order
     */
    public static function trackUsage($couponId, $orderId = null)
    {
        $coupon = Coupon::find($couponId);
        if (!$coupon) return;

        DB::table('coupon_usage')->insert([
            'coupon_id' => $coupon->id,
            'user_id' => Auth::id(),
            'customer_identifier' => request()->ip(),
            'order_id' => $orderId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $coupon->increment('usage_count');

        // Auto-deactivate if usage limit reached
        if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
            $coupon->update(['is_active' => false]);
        }
    }

    /**
     * List all coupons
     */
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return response()->json(['coupons' => $coupons]);
    }

    /**
     * Store a new coupon
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_customer' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $coupon = Coupon::create([
            'code' => strtoupper($validated['code']),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_purchase' => $validated['min_purchase'] ?? 0,
            'max_discount' => $validated['max_discount'] ?? null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'usage_limit_per_customer' => $validated['usage_limit_per_customer'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Cupón creado exitosamente',
            'coupon' => $coupon
        ], 201);
    }

    /**
     * Update a coupon
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'code' => "required|string|unique:coupons,code,{$id}",
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_customer' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'expires_at' => 'nullable|date',
        ]);

        $coupon->update([
            'code' => strtoupper($validated['code']),
            'type' => $validated['type'],
            'value' => $validated['value'],
            'min_purchase' => $validated['min_purchase'] ?? 0,
            'max_discount' => $validated['max_discount'] ?? null,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'usage_limit_per_customer' => $validated['usage_limit_per_customer'] ?? null,
            'is_active' => $validated['is_active'] ?? $coupon->is_active,
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        return response()->json([
            'message' => 'Cupón actualizado',
            'coupon' => $coupon
        ]);
    }

    /**
     * Delete a coupon
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json(['message' => 'Cupón eliminado']);
    }
}
