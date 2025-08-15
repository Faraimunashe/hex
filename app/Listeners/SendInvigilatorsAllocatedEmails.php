<?php

namespace App\Listeners;

use App\Events\InvigilatorsAllocated;
use App\Mail\InvigilatorAssignedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendInvigilatorsAllocatedEmails implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(InvigilatorsAllocated $event): void
    {
        $exam = $event->examination->loadMissing(['module', 'venue']);

        $uniqueEmails = $event->invigilators
            ->map(fn ($user) => $user->email)
            ->filter()
            ->unique();

        foreach ($uniqueEmails as $email) {
            Mail::to($email)->queue(new InvigilatorAssignedMail($exam));
        }
    }
}


