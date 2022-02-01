<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PhpParser\Node\Expr\FuncCall;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Billable;
    const ADMIN = 'ADMIN';
    const TEACHER = 'TEACHER';
    const STUDENT = 'STUDENT';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'usertype_id',
        'telephone',
        'role'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isTeacher()
    {
        return $this->role === User::TEACHER;
    }

    public function courses_learning()
    {
        return $this->belongsToMany(Course::class, "course_student");
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopePurchasedCourses()
    {
        return $this->courses_learning()->with("categories")->paginate();
    }

    public function scopeProcessedOrders()
    {
        return $this->orders()
            ->where("status", Order::SUCCESS)
            ->with("order_lines", "coupon")
            ->withCount("order_lines")
            ->paginate();
    }
}
