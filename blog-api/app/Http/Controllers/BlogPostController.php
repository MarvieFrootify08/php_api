<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogPostController extends Controller
{
    // Fetch all blog posts with cache
    public function index()
    {
        return Cache::tags(['blog_posts'])->remember('all_blog_posts', 60, function () {
            return BlogPost::all();
        });
    }

    // Fetch a single blog post by ID with cache
    public function show($id)
    {
        $cacheKey = "blog_post_{$id}";

        return Cache::tags(['blog_posts'])->remember($cacheKey, 60, function () use ($id) {
            return BlogPost::findOrFail($id);
        });
    }

    // Create a new blog post and clear related cache
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $blogPost = BlogPost::create($validated);

        // Clear the blog_posts tag cache
        Cache::tags(['blog_posts'])->flush();

        return response()->json($blogPost, 201);
    }

    // Update an existing blog post and clear specific cache
    public function update(Request $request, $id)
    {
        $blogPost = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        $blogPost->update($validated);

        // Clear the cache for this specific blog post
        $cacheKey = "blog_post_{$id}";
        Cache::tags(['blog_posts'])->forget($cacheKey);

        return response()->json($blogPost, 200);
    }

    // Delete a blog post and clear related cache
    public function destroy($id)
    {
        $blogPost = BlogPost::findOrFail($id);

        $blogPost->delete();

        // Clear the blog_posts tag cache
        Cache::tags(['blog_posts'])->flush();

        return response()->json(['message' => 'Blog post deleted'], 200);
    }

    // Pre-warm the cache
    public function preWarmCache()
    {
        Cache::tags(['blog_posts'])->rememberForever('all_blog_posts', function () {
            return BlogPost::all();
        });

        return response()->json(['message' => 'Cache pre-warmed'], 200);
    }

    // Clear all blog post caches
    public function clearCache()
    {
        Cache::tags(['blog_posts'])->flush();
        return response()->json(['message' => 'Cache cleared'], 200);
    }
}
