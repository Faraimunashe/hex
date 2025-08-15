<?php

namespace App\Listeners;

use App\Events\ExamNotification;
use App\Mail\ExamReminderMail;
use App\Models\StudentModule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendExamNotificationEmails implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(ExamNotification $event): void
    {
        $exam = $event->examination->loadMissing(['module', 'venue', 'invigilations.user']);

        // Notify invigilators (unique emails)
        $invigilatorEmails = $exam->invigilations
            ->map(fn ($i) => $i->user?->email)
            ->filter()
            ->unique();

        foreach ($invigilatorEmails as $email) {
            Mail::to($email)->queue(new ExamReminderMail($exam, 'invigilator'));
        }

        // Notify students (unique emails) registered for the exam's module
        $studentUserEmails = StudentModule::with('student.user')
            ->where('module_id', $exam->module_id)
            ->get()
            ->map(fn ($sm) => $sm->student?->user?->email)
            ->filter()
            ->unique();

        foreach ($studentUserEmails as $email) {
            Mail::to($email)->queue(new ExamReminderMail($exam, 'student'));
        }
    }
}


