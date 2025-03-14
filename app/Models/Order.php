<?php

namespace App\Models;

use App\Models\Product;
use App\Models\SaleProduct;
use App\Models\PurchaseProduct;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'name', 'phone_number', 'product_id', 'purchase_id', 'sale_id',
        'qty', 'sale_price', 'total_amount', 'discount', 'status', 'payment_method'
    ];
    // Auto Calculate Total Amount
    public function setTotalAmountAttribute()
    {
        $salePrice = $this->attributes['sale_price'];
        $qty = $this->attributes['qty'];
        $discountPercentage = $this->attributes['discount']; // In %

        // Discount Calculation
        $discountAmount = ($salePrice * $qty) * ($discountPercentage / 100);

        // Final Total Price after Discount
        $this->attributes['total_amount'] = ($salePrice * $qty) - $discountAmount;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchase()
    {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_id');
    }

    public function sale()
    {
        return $this->belongsTo(SaleProduct::class, 'sale_id');
    }
}
