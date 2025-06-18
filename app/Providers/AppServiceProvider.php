<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Додаємо необхідні use
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Observers\BlogPostObserver;
use App\Observers\BlogCategoryObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Реєструємо обсервери
        BlogPost::observe(BlogPostObserver::class);
        BlogCategory::observe(BlogCategoryObserver::class);
    }
}
