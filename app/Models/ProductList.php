<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductList extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected $attributes = [
        'stt' => 0,
        'hienthi' => 1,
        'noibat' => 0,
    ];

    protected $casts = [
        'hienthi' => 'boolean',
        'noibat' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleting(function (ProductList $list) {
            // Update sản phẩm thuộc danh mục này → id_list = null
            $list->products()->update(['id_list' => null]);

            $list->cats()->update(['id_list' => null]);

            // Nếu bạn muốn xóa luôn cat con thì thay bằng:
            // $list->cats()->delete();  // hoặc cascadeOnDelete() trong migration
        });
    }

    public function getNameAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"ten$lang"} ?? $this->tenvi;
    }

    // Relationship: 1 Danh mục cấp 1 có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'id_list');
    }

    // Relationship: 1 Danh mục cấp 1 có nhiều Danh mục cấp 2
    public function cats()
    {
        return $this->hasMany(ProductCat::class, 'id_list');
    }
}