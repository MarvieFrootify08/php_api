<?php

use App\Http\Controllers\BlogPostController;
use Illuminate\Support\Facades\Route;

// Blog Post CRUD Routes using apiResource
Route::apiResource('blog-posts', BlogPostController::class);

// Additional Cache Management Routes
Route::post('blog-posts/pre-warm-cache', [BlogPostController::class, 'preWarmCache']); // Pre-warm the cache
Route::delete('blog-posts/clear-cache', [BlogPostController::class, 'clearCache']);    // Clear all blog post caches
