<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Creator;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');
Route::get('/explore', ExploreController::class)->name('explore');

Route::get('/posts/{username}/{post}', [PostController::class, 'show'])->name('posts.show');

// Legal & company pages
Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/terms', 'legal.terms')->name('terms');
});
Route::get('/terms', [LegalController::class, 'terms'])->name('terms');
Route::get('/privacy', [LegalController::class, 'privacy'])->name('privacy');
Route::get('/content-policy', [LegalController::class, 'contentPolicy'])->name('content-policy');
Route::get('/cookies', [LegalController::class, 'cookies'])->name('cookies');
Route::get('/contact', [LegalController::class, 'contact'])->name('contact');
Route::post('/contact', [LegalController::class, 'contactSubmit'])->name('contact.submit')->middleware('throttle:5,1');

// SEO files - cached for performance
Route::get('/robots.txt', function () {
    $content = \Illuminate\Support\Facades\Cache::remember('seo:robots.txt', 86400, fn () => file_get_contents(resource_path('seo/robots.txt')));

    return response($content, 200, [
        'Content-Type' => 'text/plain',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('robots');

Route::get('/sitemap.xml', function () {
    $xml = \Illuminate\Support\Facades\Cache::remember('seo:sitemap.xml', 3600, function () {
        return view('seo.sitemap')->render();
    });

    return response($xml, 200, [
        'Content-Type' => 'application/xml',
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->name('sitemap');

/*
|--------------------------------------------------------------------------
| Guest routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('throttle:10,1');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:login');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('throttle:5,1')->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware('throttle:5,1')->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Email verification
    Route::get('/email/verify', VerificationPromptController::class)->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', EmailVerificationNotificationController::class)->middleware(['throttle:6,1'])->name('verification.send');

    // Interact with creators
    Route::post('/creator/{username}/follow', [CreatorController::class, 'follow'])->name('creator.follow');
    Route::post('/creator/{username}/subscribe', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/media/posts/{post}/{media}', [PostController::class, 'stream'])->name('posts.media.stream');

    // Subscription management
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

    // Feed (posts from followed creators)
    Route::get('/feed', FeedController::class)->name('feed');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/{notification}', [NotificationController::class, 'markAsRead'])->name('notifications.show');

    // Reporting
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

    // Blocking
    Route::get('/blocks', [BlockController::class, 'index'])->name('blocks.index');
    Route::post('/blocks/{username}', [BlockController::class, 'store'])->name('blocks.store');
    Route::delete('/blocks/{block}', [BlockController::class, 'destroy'])->name('blocks.destroy');

    // Account
    Route::get('/profile', [AccountController::class, 'index'])->name('profile.index');
    Route::put('/profile', [AccountController::class, 'update'])->name('profile.update');
    Route::get('/settings', [AccountController::class, 'settings'])->name('settings.index');
    Route::put('/settings/password', [AccountController::class, 'updatePassword'])->name('settings.password');
    Route::get('/settings/export', [AccountController::class, 'export'])->name('settings.export');
    Route::post('/settings/delete-account', [AccountController::class, 'requestDeletion'])->name('settings.delete');

    Route::middleware(['verified'])->group(function () {
        // Creator dashboard
        Route::prefix('creator')->name('creator.')->group(function () {
            Route::get('/dashboard', Creator\DashboardController::class)->name('dashboard');

            Route::get('/profile', [Creator\ProfileController::class, 'index'])->name('profile.edit');
            Route::put('/profile', [Creator\ProfileController::class, 'update'])->name('profile.update');

            Route::get('/verification', [Creator\VerificationController::class, 'index'])->name('verification');
            Route::post('/verification', [Creator\VerificationController::class, 'store'])->name('verification.store');

            Route::get('/posts', [Creator\PostController::class, 'index'])->name('posts.index');
            Route::get('/posts/create', [Creator\PostController::class, 'create'])->name('posts.create');
            Route::post('/posts', [Creator\PostController::class, 'store'])->name('posts.store');
            Route::get('/posts/{post}/edit', [Creator\PostController::class, 'edit'])->name('posts.edit');
            Route::put('/posts/{post}', [Creator\PostController::class, 'update'])->name('posts.update');
            Route::delete('/posts/{post}', [Creator\PostController::class, 'destroy'])->name('posts.destroy');
            Route::delete('/posts/{post}/media/{media}', [Creator\PostController::class, 'deleteMedia'])->name('posts.media.destroy');

            Route::get('/subscribers', Creator\SubscriberController::class)->name('subscribers');

            Route::get('/earnings', Creator\EarningsController::class)->name('earnings');
            Route::post('/earnings/payout', [Creator\EarningsController::class, 'requestPayout'])->name('earnings.payout');
        });

        // Admin panel
        Route::prefix('admin')->name('admin.')->middleware('can:manage,App\Models\User')->group(function () {
            Route::get('/', Admin\DashboardController::class)->name('dashboard');

            Route::resource('users', Admin\UserController::class)->only(['index', 'show']);
            Route::post('/users/{user}/toggle-active', [Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');
            Route::post('/users/{user}/promote', [Admin\UserController::class, 'promote'])->name('users.promote');
            Route::post('/users/{user}/demote', [Admin\UserController::class, 'demote'])->name('users.demote');
            Route::post('/users/{user}/suspend', [Admin\UserController::class, 'suspend'])->name('users.suspend');

            Route::get('/creators', [Admin\CreatorController::class, 'index'])->name('creators');
            Route::get('/creators/{verification}', [Admin\CreatorController::class, 'show'])->name('creators.show');
            Route::get('/creators/{verification}/document', [Admin\CreatorController::class, 'document'])->name('creators.document');
            Route::post('/creators/{verification}/approve', [Admin\CreatorController::class, 'approve'])->name('creators.approve');
            Route::post('/creators/{verification}/reject', [Admin\CreatorController::class, 'reject'])->name('creators.reject');
            Route::post('/creators/{profile}/feature', [Admin\CreatorController::class, 'feature'])->name('creators.feature');

            Route::get('/posts', [Admin\PostController::class, 'index'])->name('posts');
            Route::post('/posts/{post}/toggle', [Admin\PostController::class, 'toggle'])->name('posts.toggle');
            Route::delete('/posts/{post}', [Admin\PostController::class, 'destroy'])->name('posts.destroy');

            Route::get('/subscriptions', [Admin\SubscriptionController::class, 'index'])->name('subscriptions');
            Route::get('/transactions', [Admin\TransactionController::class, 'index'])->name('transactions');

            Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/{report}', [Admin\ReportController::class, 'show'])->name('reports.show');
            Route::put('/reports/{report}', [Admin\ReportController::class, 'update'])->name('reports.update');

            Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories');
            Route::post('/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
            Route::put('/categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Public creator profile (registered last so it never shadows the
| reserved /creator/* dashboard routes above)
|--------------------------------------------------------------------------
*/
Route::get('/creator/{username}', [CreatorController::class, 'show'])->name('creator.show');