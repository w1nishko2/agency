<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'images',
        'meta_title',
        'meta_description',
        'is_active',
        'content_map',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'images' => 'array',
        'content_map' => 'array',
    ];

    /**
     * Получить страницу по slug
     */
    public static function getBySlug($slug)
    {
        return static::where('slug', $slug)->where('is_active', true)->first();
    }
}
