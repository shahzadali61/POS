<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string',
            'brand_id' => 'required|exists:brands,id',
        ]);
        DB::beginTransaction();

        try {
           $product= Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'brand_id' => $request->brand_id,
                'user_id' => Auth::id(),

            ]);

            if ($product) {
                $user = Auth::user();
                $note = 'Brand "' . $product->name . '" created by ' . ($user->name ?? 'Unknown User');

                ProductLog::create([
                    'note' => $note,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'user_id' => Auth::id(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // 🛠 Debugging: Log error
            Log::error('Brand creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
    }
}
