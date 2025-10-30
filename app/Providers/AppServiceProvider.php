<?php

namespace App\Providers;

use App\Listeners\UserEventSubscriber;
use Illuminate\Support\ServiceProvider;
use App\View\Composers\SidebarComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        View::composer(
            ['layouts.sidebar', 'layouts.header'], 
            SidebarComposer::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::subscribe(UserEventSubscriber::class);
    }
}
