<?php
namespace App\Listeners;

use App\Events\ActivityPerformed;

class LogActivity
{
    /**
     * Handle the event.
     *
     * @param  ActivityPerformed  $event
     * @return void
     */
    public function handle(ActivityPerformed $event)
    {
        // Logic to handle the event goes here
        // For example, log the activity
        \Log::info('Activity performed: ' . $event->data['name']);
    }
}
