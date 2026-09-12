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
    // Public auth - throttled to mitigate brute-force / enumeration
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register');

    // Public catalog - throttled for scraping protection
    Route::get('/creators', [CreatorController::class, 'index'])->middleware('throttle:60,1')->name('creators.index');
    Route::get('/creators/{username}', [CreatorController::class, 'show'])->middleware('throttle:60,1')->name('creators.show');
    Route::get('/posts', [PostController::class, 'index'])->middleware('throttle:60,1')->name('posts.index');

    // Authenticated - rate limited per user
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::post('/posts', [PostController::class, 'store'])->middleware('throttle:30,1')->name('posts.store');

        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('/subscriptions', [SubscriptionController::class, 'store'])->middleware('throttle:10,1')->name('subscriptions.store');
        Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->middleware('throttle:20,1')->name('subscriptions.destroy');
    });
});

// Provider webhooks (public by design, verified by the gateway adapter)
Route::post('/webhooks/payment', [PaymentWebhookController::class, 'handle'])
    ->middleware('throttle:60,1')
    ->name('webhooks.payment');