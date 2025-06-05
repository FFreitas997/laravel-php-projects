<?php

namespace App\Providers;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
/*        // Allow the user to update the event if they are the owner
        Gate::define('update-event', function (User $user, Event $event) {
            return $user->id === $event->user_id;
        });

        // Allow the user to delete the event if they are the owner or the user is an attendee
        Gate::define('delete-attendee', function (User $user, Event $event, Attendee $attendee) {
            return $user->id === $event->user_id || $user->id === $attendee->user_id;
        });
*/
    }
}
