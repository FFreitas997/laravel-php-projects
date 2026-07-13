<?php

namespace App\Models;

use App\Notifications\EventReminderNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Event
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property Carbon|null $start_time
 * @property Carbon|null $end_time
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'description', 'start_time', 'end_time', 'user_id'];

    /**
     * Get the user that owns the event.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendees for the event.
     *
     * @return HasMany
     */
    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }

    /**
     * Notify all attendees of the event.
     */
    public function notifyAttendees(): void
    {
        $this->attendees->each(fn($attendee) => $attendee->user->notify(new EventReminderNotification($this)));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['start_time' => 'datetime', 'end_time' => 'datetime'];
    }
}
