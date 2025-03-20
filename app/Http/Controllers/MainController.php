<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function checkRole(){
        if (auth()->check()) {
            if (auth()->user()->role == "user") {

                return redirect()->route('user.dashboard');
            } else {

                return redirect()->route('profile.edit');
            }
        } else {

            return redirect()->route('login');
        }

    }
    public function dashboard()
    {
        $brands = Brand::where('user_id', Auth::id())->count();
        $totalProduct = Product::where('user_id', Auth::id())->count();
        $category = Category::where('user_id', Auth::id())->count();
        $totalRevenue = Order::where('user_id', Auth::id())->sum('total_price');
        $products = Product::where('user_id', Auth::id())
        ->with(['purchaseProducts' => function ($query) {
            $query->select('product_id', 'stock');
        }])
        ->get()
        ->map(function ($product) {
            $product->total_stock = $product->purchaseProducts->sum('stock');
            return $product;
        });

        return Inertia::render('admin/Dashboard', compact('brands', 'totalProduct', 'category', 'totalRevenue','products'));
    }

}
