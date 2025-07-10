<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;
    
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

    public function scopeByTags(Builder $query, array $tags): Builder
    {
        return $query->where(function ($q) use ($tags) {
            foreach ($tags as $tag) {
                $q->orWhereJsonContains('tags', $tag);
            }
        });
    }

    public function scopeSearchContent(Builder $query, string $search): Builder
    {
        return $query->where('content', 'LIKE', "%{$search}%");
    }
}
