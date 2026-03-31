<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['type', 'ho_ten', 'email', 'phone', 'noi_dung', 'da_doc'];

    protected $casts = [
        'da_doc' => 'boolean',
    ];
}
