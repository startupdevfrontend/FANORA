<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CreatorController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1
|--------------------------------------------------------------------------
| Prepared for the future mobile application. Token auth via Sanctum.
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    // Public auth
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Public catalog
    Route::get('/creators', [CreatorController::class, 'index'])->name('creators.index');
    Route::get('/creators/{username}', [CreatorController::class, 'show'])->name('creators.show');
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

    // Authenticated
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
    });
});

// Provider webhooks (public by design, verified by the gateway adapter)
Route::post('/webhooks/payment', [PaymentWebhookController::class, 'handle'])
    ->middleware('throttle:60,1')
    ->name('webhooks.payment');