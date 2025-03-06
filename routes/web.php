<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\BrandController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'user', 'verified'])->name('user.')->group(function () {
    Route::get('user/dashboard', function () {
        return Inertia::render('admin/Dashboard');
    })->name('dashboard');

    Route::get('brands', [BrandController::class, 'index'])->name('brands');
    Route::post('brand/store', [BrandController::class, 'store'])->name('brand.store');
    Route::get('brand/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
    Route::put('brand/update/{id}', [BrandController::class, 'update'])->name('brand.update');
    Route::delete('brand/delete/{id}', [BrandController::class, 'destroy'])->name('brand.delete');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [MainController::class, 'checkRole'])->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
