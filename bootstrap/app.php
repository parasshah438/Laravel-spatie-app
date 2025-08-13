<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
            Route::middleware('web')
                ->group(base_path('routes/customer.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.guest' => \App\Http\Middleware\AdminGuestMiddleware::class,
            'admin.auth' => \App\Http\Middleware\AdminMiddleware::class,
            'customer.guest' => \App\Http\Middleware\CustomerGuestMiddleware::class,
            'customer.auth' => \App\Http\Middleware\CustomerMiddleware::class,
            'track.activity' => \App\Http\Middleware\TrackUserActivity::class,
        ]);
        
        // Add activity tracking to web middleware group
        $middleware->web(append: [
            \App\Http\Middleware\TrackUserActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
