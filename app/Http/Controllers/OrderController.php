<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Product;
use App\Models\SaleProduct;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; // ✅ Correct import


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

    public function store(Request $request)
{

    $request->validate([
        'name' => 'required|string',
        'phone_number' => 'required|string',
        'payment_method' => 'nullable|string',
        'discount' => 'nullable|numeric|min:0|max:100',
        'products' => 'required|array',
 'products.*.product_id' => [
    'required',
    function ($attribute, $value, $fail) {
        if (!Product::where('id', $value)->whereNull('deleted_at')->exists()) {
            $fail("The selected {$attribute} is invalid.");
        }
    },
],
'products.*.purchase_id' => [
    'required',
    function ($attribute, $value, $fail) {
        if (!PurchaseProduct::where('id', $value)->whereNull('deleted_at')->exists()) {
            $fail("The selected {$attribute} is invalid.");
        }
    },
],

        'products.*.qty' => 'required|integer|min:1',
        'products.*.sale_price' => 'required|numeric|min:0',
    ]);

    try {
        DB::beginTransaction();
        // Calculate Total Price & Subtotal
        $totalPrice = array_sum(array_map(fn($product) => $product['sale_price'] * $product['qty'], $request->products));
        $discountAmount = ($request->discount / 100) * $totalPrice;
        $subTotal = $totalPrice - $discountAmount;

        // Create the Order
        $order = Order::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'total_price' => $totalPrice,
            'discount' => $request->discount,
            'subtotal_price' => $subTotal,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'user_id' => Auth::id(),
        ]);

        // Insert Sale Products
        foreach ($request->products as $product) {
            SaleProduct::create([
                'order_id' => $order->id,
                'product_id' => $product['product_id'],
                'purchase_id' => $product['purchase_id'],
                'user_id' => Auth::id(),
                'sale_price' => $product['sale_price'],
                'qty' => $product['qty'],
                'total_price' => $product['sale_price'] * $product['qty'],
            ]);
        }

        DB::commit();
        return redirect()->back()->with('success', 'Order created successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Failed to create order: ' . $e->getMessage()], 500);
    }
}

}
