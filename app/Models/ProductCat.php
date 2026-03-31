<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCat extends Model
{
    use HasFactory;

    protected $guarded = []; // Cho phép nhập tất cả


    protected $attributes = [
        'stt' => 0,
        'hienthi' => 1,
        'noibat' => 0,
    ];

    protected $casts = [
        'hienthi' => 'boolean',
        'noibat' => 'boolean',
    ];

    public function getNameAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"ten$lang"} ?? $this->tenvi;
    }

    // Quan hệ ngược về cấp 1
    public function list()
    {
        return $this->belongsTo(ProductList::class, 'id_list');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_cat');
    }


}