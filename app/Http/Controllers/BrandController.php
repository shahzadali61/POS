<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{

    public function index(){
        $brands = Brand::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('admin/brand/Index',compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
        ]);

        Brand::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);
        return redirect()->route('admin.brands')->with('success', 'Brand created successfully.');
    }
}
