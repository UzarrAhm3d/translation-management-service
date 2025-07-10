<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeByKey(Builder $query, string $key) {
        return $query->where('key', $key);
    }

    public function scopeByLocale(Builder $query, string $locale) {
        return $query->where('locale', $locale);
    }
}
