<?php

namespace App\Models;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'user_id',
    ];

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
    protected static function boot()
{
    parent::boot();

    static::deleting(function ($category) {
        foreach ($category->brands as $brand) {
            $brand->delete(); // Yeh brand delete karega aur brand ke deleting event se products bhi delete ho jayenge
        }
    });
}
}
