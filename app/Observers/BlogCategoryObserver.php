<?php

namespace App\Observers;

use App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogCategoryObserver
{
    /**
     * Обробка перед створенням запису.
     *
     * @param  BlogCategory  $blogCategory
     */
    public function creating(BlogCategory $blogCategory)
    {
        $this->setSlug($blogCategory);
    }

    /**
     * Обробка перед оновленням запису.
     *
     * @param  BlogCategory  $blogCategory
     */
    public function updating(BlogCategory $blogCategory)
    {
        $this->setSlug($blogCategory);
    }

    /**
     * Генерація псевдоніму, якщо він не заданий.
     *
     * @param BlogCategory  $blogCategory
     */
    protected function setSlug(BlogCategory $blogCategory)
    {
        if (empty($blogCategory->slug)) {
            $blogCategory->slug = Str::slug($blogCategory->title);
        }
    }

    /**
     * Handle the BlogCategory "created" event.
     */
    public function created(BlogCategory $blogCategory): void
    {
        //
    }

    /**
     * Handle the BlogCategory "updated" event.
     */
    public function updated(BlogCategory $blogCategory): void
    {
        //
    }

    /**
     * Handle the BlogCategory "deleted" event.
     */
    public function deleted(BlogCategory $blogCategory): void
    {
        //
    }

    /**
     * Handle the BlogCategory "restored" event.
     */
    public function restored(BlogCategory $blogCategory): void
    {
        //
    }

    /**
     * Handle the BlogCategory "force deleted" event.
     */
    public function forceDeleted(BlogCategory $blogCategory): void
    {
        //
    }
}
