<?php

namespace App\Models;

use App\Helpers\Currency;
use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, Hashidable;

    protected $fillable = [
        "user_id", "title", "description",
        "picture", "price", "featured", "status"
    ];

    const PUBLISHED = 1;
    const PENDING = 2;
    const REJECTED = 3;

    const prices = [
        '9.99' => '9.99€',
        '12.99' => '12.99€',
        '19.99' => '19.99€',
        '29.99' => '29.99€',
        '49.99' => '49.99€'
    ];

    protected $appends = [
        "rating", "formatted_price"
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

    public function imagePath()
    {
        return sprintf('%s/%s', '/storage/courses', $this->picture);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, "course_student");
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class)->orderBy("order", "asc");
    }

    public function getRatingAttribute()
    {
        return $this->reviews->avg('stars');
    }

    public function getFormattedPriceAttribute()
    {
        return Currency::formatCurrency($this->price);
    }

    public function totalVideoUnits()
    {
        return $this->units->where("unit_type", Unit::VIDEO)->count();
    }

    public function totalFileUnits()
    {
        return $this->units->where("unit_type", Unit::ZIP)->count();
    }

    public function totalTime()
    {
        $minutes = $this->units->where("unit_type", Unit::VIDEO)->sum("unit_time");
        return gmdate("H:i", $minutes * 60);
    }

    public function scopeFiltered(Builder $builder)
    {
        $builder->with("teacher");
        $builder->withCount("students");
        $builder->where("status", Course::PUBLISHED);
        if (session()->has('search[courses]')) {
            $builder->where('title', 'LIKE', '%' . session('search[courses]') . '%');
        }
        return $builder->paginate(4);
    }

    public function scopeForTeacher(Builder $builder)
    {
        return $builder
            ->withCount('students')
            ->where("user_id", auth()->id())
            ->paginate(4);
    }
}
