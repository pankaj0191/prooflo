<?php

namespace App\Listeners\Prooflo\Subscription;

class UpdateTrialEndingDate
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @return void
     */
    public function handle($event)
    {
        $event->user->forceFill([
            'trial_ends_at' => $event->user->subscription()->trial_ends_at,
        ])->save();
    }
}
