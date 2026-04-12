<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'usage_count',
        'usage_limit_per_customer',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'expires_at' => 'date',
    ];

    /**
     * Validate if coupon can be applied
     */
    public function isValid(float $purchaseAmount): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Cupón desactivado'];
        }

        if ($this->expires_at && Carbon::now()->gt($this->expires_at)) {
            return ['valid' => false, 'message' => 'Cupón expirado'];
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Cupón agotado'];
        }

        if ($purchaseAmount < $this->min_purchase) {
            return ['valid' => false, 'message' => "Compra mínima: $".number_format($this->min_purchase, 2)];
        }

        return ['valid' => true, 'message' => 'Cupón válido'];
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount(float $purchaseAmount): float
    {
        $discount = $this->type === 'percentage'
            ? $purchaseAmount * ($this->value / 100)
            : $this->value;

        // Apply max discount cap if set
        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        return min($discount, $purchaseAmount);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}
