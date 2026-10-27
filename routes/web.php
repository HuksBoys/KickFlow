<?php

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FieldController;
use App\Models\Field;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    $fields = Field::where('is_active', true)->take(6)->get();

    return view('welcome', compact('fields'));
})->name('home');

// Fields
Route::get('/fields', [FieldController::class, 'index'])->name('fields.index');
Route::get('/fields/{field}', [FieldController::class, 'show'])->name('fields.show');

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Authenticated customer/admin routes
Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// API routes
Route::prefix('api')->group(function () {
    Route::post('/chat', [ChatController::class, 'chat'])->name('api.chat');
    Route::get('/fields/{field}/schedules', [FieldController::class, 'schedules'])->name('api.fields.schedules');
});
