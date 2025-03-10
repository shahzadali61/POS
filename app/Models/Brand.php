<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'user_id',
        'category_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
