<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery_images',
        'tags',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'status',
        'published_at',
        'read_time',
        'is_featured',
        'allow_comments',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'tags' => 'array',
        'meta_keywords' => 'array',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
    ];

    protected $appends = ['reading_time'];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getReadingTimeAttribute()
    {
        if ($this->read_time) {
            return $this->read_time;
        }
        
        $words = str_word_count(strip_tags($this->content));
        return ceil($words / 200); // 200 слов в минуту
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRecent($query, $limit = 5)
    {
        return $query->published()
                    ->orderBy('published_at', 'desc')
                    ->limit($limit);
    }
}
