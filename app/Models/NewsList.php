<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsList extends Model
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
        static::deleting(function (NewsList $list) {
            // Update bài viết thuộc danh mục này → id_list = null
            $list->news()->update(['id_list' => null]);
            // Update danh mục cấp 2 thuộc danh mục này → id_list = null
            $list->cats()->update(['id_list' => null]);
        });
    }

    // Helper lấy tên theo ngôn ngữ đang chọn
    public function getNameAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"ten$lang"} ?? $this->tenvi;
    }

    // Relationship: 1 Danh mục cấp 1 có nhiều bài viết
    public function news()
    {
        return $this->hasMany(News::class, 'id_list');
    }

    // Relationship: 1 Danh mục cấp 1 có nhiều Danh mục cấp 2
    public function cats()
    {
        return $this->hasMany(NewsCat::class, 'id_list');
    }
}
