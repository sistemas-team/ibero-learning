<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, Hashidable, SoftDeletes;

    const PERCENT = 'PERCENT';
    const PRICE = 'PRICE';

    protected $fillable = [
        'user_id', 'code', 'discount_type',
        'discount', 'description', 'enabled', 'expires_at'
    ];
    protected $dates = [
        "expires_at"
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::saving(function ($table) {
                $table->user_id = auth()->id();
            });
        }
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function scopeForTeacher(Builder $builder)
    {
        return $builder
            ->where("user_id", auth()->id())
            ->paginate();
    }

    public function scopeAvailable(Builder $builder, string $code)
    {
        return $builder
            ->where("enabled", true)
            ->where("code", $code)
            ->where('expires_at', '>=', now())
            ->orWhereNull('expires_at');
    }

    public static function discountTypes()
    {
        return [
            self::PERCENT => __("Porcentaje"),
            self::PRICE => __("Fijo"),
        ];
    }
}
