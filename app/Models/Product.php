<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'user_id',
        'brand_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
