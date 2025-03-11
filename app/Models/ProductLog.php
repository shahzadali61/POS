<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    protected $fillable = [
        'note',
        'product_name',
        'user_id',
        'product_id',
    ];
}
