<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
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

    public function getNameAttribute()
    {
        $lang = app()->getLocale();
        return $this->{"ten$lang"} ?? $this->tenvi;
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_brand');
    }
}
