<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Order;
use App\Models\Review;
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
        // Data notifikasi navbar admin: hitung dari database (bukan dummy statis).
        View::composer('layouts.admin', function ($view) {
            try {
                $notifOrderCount = Order::whereIn('status', ['pending_payment', 'waiting_verification', 'processing', 'shipped'])->count();
                $notifContactCount = Contact::count();
                $notifReviewCount = Review::count();
            } catch (\Throwable $e) {
                $notifOrderCount = 0;
                $notifContactCount = 0;
                $notifReviewCount = 0;
            }

            $view->with(compact('notifOrderCount', 'notifContactCount', 'notifReviewCount'));
        });
    }
}
