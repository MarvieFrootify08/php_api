<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogPostController;



Route::apiResource('posts', BlogPostController::class);


