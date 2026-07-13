<?php

namespace App\Policies;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttendeePolicy
{
    /**
     * Determine whether the user can view any models.
     * ?User means that the user can be null, allowing unauthenticated users to view attendees.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * ?User means that the user can be null, allowing unauthenticated users to view the attendee.
     */
    public function view(?User $user, Attendee $attendee): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     * User means that the user must be authenticated to create an attendee.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event, Attendee $attendee): bool
    {
        return $user->id === $event->user_id || $user->id === $attendee->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Attendee $attendee): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Attendee $attendee): bool
    {
        return false;
    }
}
