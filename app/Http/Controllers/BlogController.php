<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::query()->published()->with('category', 'author');

        // Фильтр по категории
        if ($request->filled('category')) {
            $category = BlogCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(12);

        $categories = BlogCategory::withCount('publishedPosts')->get();
        $popularPosts = BlogPost::published()
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'popularPosts'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)->published()->firstOrFail();
        $post->incrementViews();

        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
