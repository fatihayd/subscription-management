<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::controller(UserController::class)
    ->prefix('user')
    ->middleware('auth:sanctum')
    ->group(function (){
        Route::post('{id}/subscription', 'addSubscription');
        Route::put('{id}/subscription/{subscription_id}', 'updateSubscription');
        Route::delete('{id}/subscription/{subscription_id}','deleteSubscription');
        Route::delete('{id}/subscriptions', 'deleteSubscriptions');

        Route::post('{id}/transaction', 'addTransaction');

        Route::get('{id}', 'getSubscriptionAndTransactions');
});
