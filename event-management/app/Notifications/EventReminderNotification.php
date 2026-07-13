<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * ShouldQueue is implemented to ensure that the notification is queued for processing, which is useful for sending emails asynchronously and improving application performance.
 * To process the queued notifications, run the following command:  php artisan queue:work
 */
class EventReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Event $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $eventName = $this->event->name;
        $eventDescription = $this->event->description;
        $eventStart = $this->event->start_time;

        return (new MailMessage)
            ->greeting('Hello!')
            ->subject('Event Reminder')
            ->line("You've been reminded to receive notifications for the event: $eventName.")
            ->line("Event $eventName from $eventDescription")
            ->line("Starting at $eventStart")
            ->action('View event', route('events.show', $this->event->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'event_description' => $this->event->description,
            'event_start_time' => $this->event->start_time,
        ];
    }
}
