<?php

use App\Http\Controllers\Admin\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('api.admin.')->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
});
