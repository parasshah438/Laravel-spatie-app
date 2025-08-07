<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('dashboard', function ($user) {
    // Allow all authenticated users to listen to general dashboard updates
    return true;
});

Broadcast::channel('dashboard.admin', function ($user) {
    // Only allow admin users
    return $user && $user->hasRole('Admin');
});

Broadcast::channel('dashboard.web', function ($user) {
    // Allow regular web users
    return auth('web')->check();
});

Broadcast::channel('dashboard.customer', function ($user) {
    // Allow customer users
    return auth('customer')->check();
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
