<?php

namespace App\Providers;

use App\Services\NotificationService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('partials.header', function ($view) {
            $userId = session('user_id');
            $notificationCount = 0;

            if ($userId) {
                $notificationCount = app(NotificationService::class)->unreadCount($userId);
            }

            $view->with('notificationCount', $notificationCount);
        });
    }
}
