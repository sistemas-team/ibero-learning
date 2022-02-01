<?php

namespace App\Models;

use App\Traits\Hashidable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory, Hashidable;

    protected $fillable = [
        "title", "content", "course_id", "user_id",
        "unit_type", "unit_time", "file", "order", "free"
    ];

    const ZIP = 'ZIP';
    const VIDEO = 'VIDEO';
    const SECTION = 'SECTION';

    protected static function boot()
    {
        parent::boot();

        self::saving(function ($table) {
            $table->user_id = auth()->id();
        });

        self::creating(function ($table) {
            $last = Unit::whereCourseId(request("course_id"))
                ->orderBy('order', 'desc')
                ->take(1)
                ->first();
            $table->order = $last ? $last->order += 1 : 1;
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeForTeacher(Builder $builder)
    {
        return $builder
            ->with('course')
            ->where("user_id", auth()->id())
            ->paginate();
    }

    public static function unitTypes()
    {
        return [
            self::ZIP, self::VIDEO, self::SECTION
        ];
    }
}
