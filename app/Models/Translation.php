<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $table = 'translations';

    protected $fillable = [
        'locale',
        'key',
        'value',
        'tags',
        'cdn_ready',
    ];

    protected $casts = [
        'tags' => 'array',
        'cdn_ready' => 'boolean',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['key'] ?? false,
            fn($query, $key) =>
            $query->where('key', 'like', '%' . $key . '%')
        );

        $query->when(
            $filters['value'] ?? false,
            fn($query, $value) =>
            $query->where('value', 'like', '%' . $value . '%')
        );

        $query->when(
            $filters['locale'] ?? false,
            fn($query, $locale) =>
            $query->where('locale', $locale)
        );

        $query->when(
            $filters['tags'] ?? false,
            fn($query, $tags) => $query->where(function ($q) use ($tags) {
                $tags = is_array($tags) ? $tags : explode(',', $tags);
                foreach ($tags as $tag) {
                    $q->whereJsonContains('tags', $tag);
                }
            })
        );
    }
}
