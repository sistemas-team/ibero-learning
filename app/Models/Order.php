<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    const SUCCESS = "SUCCESS";
    const PENDING = "PENDING";

    protected $appends = [
        "formatted_total_amount",
        "formatted_status",
        "coupon_code"
    ];

    public function order_lines() {
        return $this->hasMany(OrderLine::class);
    }

    public function coupon() {
        return $this->belongsTo(Coupon::class);
    }

    public function getFormattedTotalAmountAttribute() {
        if ($this->total_amount) {
            return Currency::formatCurrency($this->total_amount, true);
        }
        return Currency::formatCurrency(0);
    }

    public function getFormattedStatusAttribute() {
        return $this->status === self::SUCCESS ? __("Procesado") : __("Pendiente");
    }

    public function getCouponCodeAttribute(): string {
        if ($this->coupon_id) {
            return $this->coupon->code;
        }
        return "N/A";
    }
}
