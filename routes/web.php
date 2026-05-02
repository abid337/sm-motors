<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ItemController as FrontItemController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\ItemController as UserItemController;

// ─────────────────────────────
// FRONTEND ROUTES
// ─────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [FrontItemController::class, 'search'])->name('items.search');
Route::get('/item/{slug}', [FrontItemController::class, 'show'])->name('items.show');
Route::post('/inquiry', [FrontItemController::class, 'inquiry'])->name('items.inquiry');

// ─────────────────────────────
// USER AUTH ROUTES
// ─────────────────────────────
Route::get('/register', [AuthController::class, 'registerForm'])->name('user.register');
Route::post('/register', [AuthController::class, 'register'])->name('user.register.post');
Route::get('/login', [AuthController::class, 'loginForm'])->name('user.login');
Route::post('/login', [AuthController::class, 'login'])->name('user.login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');

// ─────────────────────────────
// USER DASHBOARD ROUTES (Protected)
// ─────────────────────────────
Route::prefix('dashboard')
    ->middleware(['auth'])
    ->name('user.')
    ->group(function () {
        Route::get('/', [UserItemController::class, 'dashboard'])->name('dashboard');
        Route::get('/items/create', [UserItemController::class, 'create'])->name('items.create');
        Route::post('/items', [UserItemController::class, 'store'])->name('items.store');
        Route::get('/items/{item}/edit', [UserItemController::class, 'edit'])->name('items.edit');
        Route::put('/items/{item}', [UserItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{item}', [UserItemController::class, 'destroy'])->name('items.destroy');
        Route::delete('/items/media/{media}', [UserItemController::class, 'deleteMedia'])->name('items.media.delete');
    });

// ─────────────────────────────
// ADMIN AUTH ROUTES
// ─────────────────────────────
Route::get('/admin/login', function () {
    return view('admin.auth.login');
})->name('admin.login');

Route::post('/admin/login', [DashboardController::class, 'loginPost'])->name('admin.login.post');
Route::post('/admin/logout', [DashboardController::class, 'logout'])->name('admin.logout');

// ─────────────────────────────
// ADMIN ROUTES (Protected)
// ─────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Items
        Route::resource('items', ItemController::class);
        Route::delete('items/media/{media}', [ItemController::class, 'deleteMedia'])->name('items.media.delete');

        // Categories
        Route::resource('categories', CategoryController::class);

        // Cities
        Route::resource('cities', CityController::class);

        // Inquiries
        Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::delete('inquiries/{id}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');
    });