<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "course_id", "stars", "review"];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function author() {
        return $this->belongsTo(User::class, "user_id");
    }
}
