<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
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
    public function dashboard(){
        $brands = Brand::where('user_id', Auth::id())->count();
        $product = Product::where('user_id', Auth::id())->count();
        $category = Category::where('user_id', Auth::id())->count();
        return Inertia::render('admin/Dashboard', compact('brands','product','category'));
    }
}
