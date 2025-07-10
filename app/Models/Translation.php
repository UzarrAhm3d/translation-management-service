<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'key',
        'locale',
        'content',
        'tags'
    ];

    protected $casts = [
        'tags' => "array"
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
