<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify every event attendee that the event will start in the next 24 hours';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting sending event reminders...');

        $query = Event::with('attendees.user')->whereBetween('start_time', [now(), now()->addDay()]);

        $count = $query->count();
        $eventLabel = Str::plural('event', $count);

        $events = $query->get();

        $this->info("Found $count $eventLabel starting in the next 24 hours.");

        // Send notifications to attendees of each event
        $events->each(fn($event) => $event->notifyAttendees());

        $this->info('Reminder notification sent successfully.');
    }
}
