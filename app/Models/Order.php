<?php

namespace App\Models;

use App\Models\Product;
use App\Models\SaleProduct;
use App\Models\PurchaseProduct;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'name', 'phone_number',
        'total_price',
         'discount', 'subtotal_price', 'status', 'payment_method', 'user_id'
    ];

    public function saleProduct()
    {
        return $this->hasMany(SaleProduct::class, 'order_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
