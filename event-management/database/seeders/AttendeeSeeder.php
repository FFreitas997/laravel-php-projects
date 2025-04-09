<?php

namespace Database\Seeders;

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Random\RandomException;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     * @throws RandomException
     */
    public function run(): void
    {
        $users = User::all();
        $events = Event::all();

        foreach ($users as $user) {

            $eventsToAttend = $events->random(random_int(1, 3));

            foreach ($eventsToAttend as $event) {
                Attendee::create(['user_id' => $user->id, 'event_id' => $event->id]);
            }
        }
    }
}
