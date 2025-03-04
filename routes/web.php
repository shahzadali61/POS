<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->name('admin.')->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('admin/Dashboard');
    })->name('dashboard');

    Route::get('brands', [BrandController::class, 'index'])->name('brands');
    Route::post('brand/store', [BrandController::class, 'store'])->name('brand.store');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
