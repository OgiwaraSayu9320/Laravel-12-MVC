<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCat extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $attributes = [
        'stt' => 0,
        'hienthi' => 1,
        'noibat' => 0,
    ];

    protected $casts = [
        'id_list' => 'integer',
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
        return $this->belongsTo(NewsList::class, 'id_list');
    }

    // Quan hệ với bài viết
    public function news()
    {
        return $this->hasMany(News::class, 'id_cat');
    }
}
