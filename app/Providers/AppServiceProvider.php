<?php

namespace App\Providers;

use App\Models\Post;
use App\Observers\PostObserver;
use App\Observers\PostOserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
    protected $observers = [
        Post::class => [PostObserver::class],
    ];

    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
