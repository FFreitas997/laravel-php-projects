<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     * ?User means that the user can be null, allowing unauthenticated users to view events.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * ?User means that the user can be null, allowing unauthenticated users to view the event.
     */
    public function view(?User $user, Event $event): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     * User means that the user must be authenticated to create an event.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return $user->id === $event->user_id;
    }
}
