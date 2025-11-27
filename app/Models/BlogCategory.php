<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'posts_count',
    ];

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }

    public function publishedPosts()
    {
        return $this->hasMany(BlogPost::class, 'category_id')
                    ->published();
    }

    public function updatePostsCount()
    {
        $this->update([
            'posts_count' => $this->publishedPosts()->count()
        ]);
    }
}
