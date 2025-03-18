<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\PurchaseProduct;
use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
    protected $fillable = ['order_id','product_id', 'purchase_id', 'user_id', 'qty', 'sale_price', 'total_price', 'status'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function purchase()
    {
        return $this->belongsTo(PurchaseProduct::class, 'purchase_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
