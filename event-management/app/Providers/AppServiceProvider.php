<?php

namespace App\Providers;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Define a gate for updating an event. This gate checks if the authenticated user is the owner of the event.
         */
        Gate::define('update-event', fn(User $user, Event $event) => $user->id === $event->user_id);

        /**
         * Define a gate for deleting an attendee. This gate checks if the authenticated user is the owner of the event or the attendee.
         */
        Gate::define('delete-attendee', fn(User $user, Event $event, Attendee $attendee) => $user->id === $event->user_id || $user->id === $attendee->user_id);

        /**
         *  Define a rate limiter for the API. This rate limiter allows a maximum of 60 requests per minute per user or IP address.
         */
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
