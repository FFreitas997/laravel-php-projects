# Laravel Projects

This repository contains some laravel projects:

1. Task Management app
2. Book Reviews app
3. Event Management app
4. Livewire Poll app
5. Job Board app

## Laravel Commands

- `composer create-project laravel/laravel example-app` - Create a new Laravel project
- `php artisan serve` - Start the server
- `php artisan migrate` - Run the migrations
- `php artisan migrate:rollback` - Rollback the last migration
- `php artisan route:list` - List all the routes
- `php artisan make:controller ExampleController` - Create a new controller
- `php artisan make:controller ExampleController --resource` - Create a new controller with resource methods
- `php artisan make:model Example -m` - Create a new model with a migration file
- `php artisan make:factory ExampleFactory --model=Example` - Create a new factory for the Example model
- `php artisan make:seeder ExampleSeeder` - Create a new seeder
- `php artisan db:seed` - Seed the database
- `php artisan migrate:refresh --seed` - Refresh the database and seed it
- `php artisan tinker` - Open the tinker shell
- `php artisan make:middleware ExampleMiddleware` - Create new middleware
- `php artisan make:mail ExampleMail` - Create new mail
- `php artisan make:request ExampleRequest` - Create new form request
- `php artisan make:controller Api/AttendeeController --api`
- `php artisan migrate:refresh --seed` - Apply all migration and seeder also
- `php artisan make:resource ExampleResource` - create resource class
- `php artisan make:policy AttendeePolicy --model=Attendee` - create a policy for a specific model
- `php artisan make:command SendEventReminders` - create custom commands
- `php artisan app:send-event-reminders` - run custom commands
- `php artisan` - list of all available commands
- `php artisan schedule:work` - Runs the scheduler in the foreground in a loop — perfect for local dev, no cron needed
- `php artisan schedule:list` - Shows all scheduled tasks and their next run time — great for sanity-checking
- `php artisan schedule:run` - Runs any due tasks once (this is what cron calls every minute in production)
- `php artisan make:notification EventReminder` - Scaffolds a notification class in app/Notifications/ with via(), toMail(), toDatabase(), etc.
- `php artisan notifications:table` - Generates the migration for the notifications table (needed for database-channel notifications)
- `php artisan make:mail EventReminderMailScaffolds` - full Mailable class, if you want richer emails than a notification's toMail() offers
- `php artisan queue:work` - starts a queue worker — a long-running process that pulls jobs off your queue and executes them, one after another.