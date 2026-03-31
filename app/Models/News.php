<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $attributes = [
        'stt' => 0,
        'hienthi' => 1,
        'noibat' => 0,
        'luotxem' => 0,
    ];

    protected $casts = [
        'id_list' => 'integer',
        'id_cat' => 'integer',
        'hienthi' => 'boolean',
        'noibat' => 'boolean',
    ];

    // Helper lấy tên theo ngôn ngữ đang chọn
    public function getNameAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"ten$lang"} ?? $this->tenvi;
    }

    // Helper lấy mô tả đa ngôn ngữ
    public function getDescAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"mota$lang"} ?? $this->motavi;
    }

    // Relationship: Thuộc danh mục cấp 1
    public function list()
    {
        return $this->belongsTo(NewsList::class, 'id_list');
    }

    // Relationship: Thuộc danh mục cấp 2
    public function cat()
    {
        return $this->belongsTo(NewsCat::class, 'id_cat');
    }

    // Relationship: Gallery
    public function galleries()
    {
        // Lấy ảnh có id_parent = id bài viết VÀ type = 'bai-viet'
        return $this->hasMany(Gallery::class, 'id_parent')
            ->where('type', 'bai-viet')
            ->orderBy('stt', 'asc')
            ->orderBy('id', 'desc');
    }
}
