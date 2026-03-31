<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'title_seo',
        'desc_seo',
        'keyword_seo',
        'image_seo'
    ];
}
