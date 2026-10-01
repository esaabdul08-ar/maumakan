<?php

use App\Http\Controllers\FoodController; // <-- TAMBAHKAN BARIS INI
use App\Http\Controllers\OrderController; // <-- TAMBAHKAN BARIS INI
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrderController::class, 'index'])->name('customer.index');
Route::post('/checkout', [OrderController::class, 'store'])->name('customer.checkout');

Route::get('/dashboard', [OrderController::class, 'adminDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Alias dashboard admin + update status pesanan (dipakai dashboard.blade.php)
    Route::get('/admin/dashboard', [OrderController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::patch('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    Route::resource('admin/foods', FoodController::class); // <-- TAMBAHKAN BARIS INI
});

require __DIR__.'/auth.php';
