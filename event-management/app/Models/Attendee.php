<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Attendee
 *
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static create(array $array)
 */
class Attendee extends Model
{

    /**
     * The attributes that are mass assignable.
     * @var list<string>
     */
    protected $fillable = ['user_id', 'event_id'];

    /**
     * Get the user that owns the attendee.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event that the attendee belongs to.
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
