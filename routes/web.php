<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestTestController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\Blog\Admin\CategoryController;
use App\Http\Controllers\Blog\Admin\PostController as AdminPostController;
use App\Http\Controllers\DiggingDeeperController;



Route::prefix('admin/blog')->group(function () {
    Route::resource('categories', CategoryController::class)
        ->only(['index', 'edit', 'store', 'update', 'create'])
        ->names('blog.admin.categories');

    // BlogPost
    Route::resource('posts', AdminPostController::class)
        ->except(['show']) // не робити маршрут для show
        ->names('blog.admin.posts');
});
Route::group(['prefix' => 'digging_deeper'], function () {
    Route::get('process-video', [\App\Http\Controllers\DiggingDeeperController::class, 'processVideo'])
        ->name('digging_deeper.processVideo');

    Route::get('prepare-catalog', [\App\Http\Controllers\DiggingDeeperController::class, 'prepareCatalog'])
        ->name('digging_deeper.prepareCatalog');


Route::get('collections', [DiggingDeeperController::class, 'collections'])

        ->name('digging_deeper.collections');

});
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::resource('rest', RestTestController::class)->names('restTest');

Route::group(['prefix' => 'blog'], function () {
    Route::resource('posts', PostController::class)->names('blog.posts');
});
