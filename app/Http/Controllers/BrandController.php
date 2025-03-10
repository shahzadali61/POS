<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\BrandLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class BrandController extends Controller
{

    public function index()
    {
        $brands = Brand::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('admin/brand/Index', compact('brands'));
    }
    public function edit($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            return Inertia::render('admin/brand/Edit', compact('brand'));
        }
        return redirect()->back()->with('error', 'Brand not found.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);
        DB::beginTransaction();

        try {
            $brand = Brand::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'category_id' => $request->category_id,

            ]);

            if ($brand) {
                $user = Auth::user();
                $note = 'Brand "' . $brand->name . '" created by ' . ($user->name ?? 'Unknown User');

                BrandLog::create([
                    'note' => $note,
                    'brand_id' => $brand->id,
                    'brand_name' => $brand->name,
                    'user_id' => Auth::id(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Brand created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // 🛠 Debugging: Log error
            Log::error('Brand creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
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
    public function destroy($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            $brand->delete();
            return redirect()->back()->with('success', 'Brand deleted successfully.');
        }
        return redirect()->back()->with('error', 'Brand not found.');
    }
}
