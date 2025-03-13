<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseProduct;
use App\Models\PurchaseProductLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PurchaseProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'purchase_price' => ['required', 'numeric'],
                    'sale_price' => [
                'required',
                'numeric',
                'gte:purchase_price' // Ensures sale_price >= purchase_price
            ],
            'qty' => ['required', 'integer', 'min:1'],
            'product_id' => ['required', 'exists:products,id'],
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $purchaseProduct = PurchaseProduct::create([
                'user_id' => Auth::id(),
                'purchase_price' => $request->purchase_price,
                'sale_price' => $request->sale_price,
                'qty' => $request->qty,
                'remaining_qty' => $request->qty,
                'product_id' => $request->product_id,
                'description' => $request->description,
            ]);
            $user = Auth::user();
            $note = 'Product "' . $purchaseProduct->product->name . '" purchased by ' . ($user->name ?? 'Unknown User') . '. ' .
            'Purchase Price: ' . $purchaseProduct->purchase_price . ', ' .
            'Sale Price: ' . $purchaseProduct->sale_price . ', ' .
            'Stock: ' . $purchaseProduct->qty . '.';
            PurchaseProductLog::create([
                'note' => $note,
                'product_name' => $purchaseProduct->product->name,
                'purchase_id' => $purchaseProduct->id,
                'user_id' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Purchase product Detail stored successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase product creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
    }
}
