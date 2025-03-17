<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orderCreate()
{
    $products = Product::with('purchaseProducts')
        ->where('user_id', Auth::id())
        ->get();

    return Inertia::render('admin/order/Create', [
        'products' => $products
    ]);
}
}
