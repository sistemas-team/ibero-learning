<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StripeWebHoockController;
use App\Http\Controllers\StudetController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');


Auth::routes();
Route::post('stripe/webhook', [StripeWebHoockController::class, 'handleWebhook']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/search', [CourseController::class, 'search'])->name('courses.search');
    Route::get('/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/{course}/learn', [CourseController::class, 'learn'])
        ->name('courses.learn')->middleware("can_access_to_course");

    Route::get('/{course}/review',[CourseController::class, 'createReview'])
        ->name('courses.reviews.create');
    Route::post('/{course}/review',[CourseController::class, 'storeReview'])
        ->name('courses.reviews.store');
});

Route::prefix('teacher')->middleware(['auth', 'teacher'])->group(function () {
    Route::get('/',  [TeacherController::class, 'index'])->name('teacher.index');
    Route::get('/courses', [TeacherController::class, 'courses'])
        ->name('teacher.courses');

    Route::get('/courses/create', [TeacherController::class, 'createCourse'])
        ->name('teacher.courses.create');

    Route::post('/courses/store', [TeacherController::class, 'storeCourse'])
        ->name('teacher.courses.store');

    Route::get('/courses/{course}', [TeacherController::class, 'editCourse'])
        ->name('teacher.courses.edit');
    Route::put('/courses/{course}', [TeacherController::class, 'updateCourse'])
        ->name('teacher.courses.update');
    //units
    Route::get('/units', [TeacherController::class, 'units'])
        ->name('teacher.units');

    Route::get('/units/create', [TeacherController::class, 'createUnit'])->name('teacher.units.create');

    Route::post('/units/store', [TeacherController::class, 'storeUnit'])
        ->name('teacher.units.store');
    Route::get('/units/{unit}', [TeacherController::class, 'editUnit'])
        ->name('teacher.units.edit');
    Route::put('/units/{unit}', [TeacherController::class, 'updateUnit'])
        ->name('teacher.units.update');
    Route::delete('/units/{unit}', [TeacherController::class, 'destroyUnit'])
        ->name('teacher.units.destroy');
    //cupons
    Route::get('/coupons', [TeacherController::class, 'coupons'])
        ->name('teacher.coupons');
    Route::get('/coupons/create', [TeacherController::class, 'createCoupon'])
        ->name('teacher.coupons.create');
    Route::post('/coupons/store', [TeacherController::class, 'storeCoupon'])
        ->name('teacher.coupons.store');
    Route::get('/coupons/{coupon}', [TeacherController::class, 'editCoupon'])
        ->name('teacher.coupons.edit');
    Route::put('/coupons/{coupon}', [TeacherController::class, 'updateCoupon'])
        ->name('teacher.coupons.update');
    Route::delete('/coupons/{coupon}', [TeacherController::class, 'destroyCoupon'])
        ->name('teacher.coupons.destroy');
});

Route::prefix('student')->middleware(['auth'])->group(function () {
    Route::get('/', [StudetController::class, 'index'])->name('student.index');

    Route::get("credit-card", [BillingController::class, 'creditCardForm'])
        ->name("student.billing.credit_card_form");

    Route::post("credit-card", [BillingController::class, 'processCreditCardForm'])
        ->name("student.billing.process_credit_card");

    Route::get('/courses', [StudetController::class, 'courses'])
        ->name('student.courses');

    Route::get('/orders', [StudetController::class, 'orders'])
        ->name('student.orders');
    Route::get('/orders/{order}', [StudetController::class, 'showOrder'])
        ->name('student.orders.show');
    Route::get('/orders/{order}/download_invoice', [StudetController::class, 'downloadInvoice'])
        ->name('student.orders.download_invoice');
});

Route::get('/add-course-to-cart/{course}', [StudetController::class, 'addCourseToCart'])
    ->name('add_course_to_cart');
Route::get('/cart', [StudetController::class, 'showCart'])
    ->name('cart');
Route::get('/remove-course-from-cart/{course}', [StudetController::class, 'removeCourseFromCart'])
    ->name('remove_course_from_cart');
Route::post('/apply-coupon', [StudetController::class, 'applyCoupon'])
    ->name('apply_coupon');


Route::group(["middleware" => ["auth"]], function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout_form');

    Route::post('/checkout', [CheckoutController::class, 'processOrder'])
        ->name('process_checkout');
});
