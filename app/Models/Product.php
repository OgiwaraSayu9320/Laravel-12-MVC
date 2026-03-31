<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = []; // Cho phép nhập mọi trường -> nếu có sửa thì thay hết filltable thành cái này

    protected $attributes = [
        'stt' => 0,
        'hienthi' => 1,
        'noibat' => 0,
        'banchay' => 0,
    ];

    protected $casts = [
        'gallery' => 'array',
        'hienthi' => 'boolean',
        'noibat' => 'boolean',
        'banchay' => 'boolean',
    ];

    // Helper lấy tên theo ngôn ngữ đang chọn
    public function getNameAttribute()
    {
        $lang = app()->getLocale(); // Lấy ngôn ngữ hiện tại (vi hoặc en)
        return $this->{"ten$lang"} ?? $this->tenvi;
    }

    // Helper lấy mô tả
    public function getDescAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"mota$lang"} ?? $this->motavi;
    }

    // Helper lấy slug (đường dẫn không dấu) có fallback
    public function getSlugAttribute()
    {
        $lang = app()->getLocale();
        $slug = $this->{"tenkhongdau$lang"};
        return !empty($slug) ? $slug : 'product-' . $this->id;
    }

    // Relationship
    public function list()
    {
        return $this->belongsTo(ProductList::class, 'id_list');
    }

    public function cat()
    {
        return $this->belongsTo(ProductCat::class, 'id_cat');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'id_brand');
    }

    public function galleries()
    {
        // Lấy ảnh có id_parent = id sản phẩm VÀ type = 'san-pham'
        // Sắp xếp theo số thứ tự (stt) rồi mới đến id
        return $this->hasMany(Gallery::class, 'id_parent')
            ->where('type', 'san-pham')
            ->orderBy('stt', 'asc')
            ->orderBy('id', 'desc');
    }
}