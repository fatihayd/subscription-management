<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\SubscriptionService;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AuthService::class, function ($app){
            return new AuthService();
        });
        $this->app->singleton(SubscriptionService::class, function ($app){
            return new SubscriptionService();
        });
        $this->app->singleton(TransactionService::class, function ($app){
            return new TransactionService();
        });
    }

    public function boot(): void
    {
        //
    }
}
