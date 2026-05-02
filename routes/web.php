<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ItemController as FrontItemController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\InquiryController;

// ─────────────────────────────
// FRONTEND ROUTES
// ─────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [FrontItemController::class, 'search'])->name('items.search');
Route::get('/item/{slug}', [FrontItemController::class, 'show'])->name('items.show');
Route::post('/inquiry', [FrontItemController::class, 'inquiry'])->name('items.inquiry');

// ─────────────────────────────
// AUTH ROUTES
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