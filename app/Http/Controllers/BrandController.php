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
    public function edit($id){
        $brand=Brand::find($id);
        if($brand){
            return Inertia::render('admin/brand/Edit',compact('brand'));

        }
        return redirect()->back()->with('error', 'Brand not found.');
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $brand = Brand::find($id);
        if (!$brand) {
            return redirect()->back()->with('error', 'Brand not found.');
        }

        $brand->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->back()->with('success', 'Brand Update successfully');
    }
}
