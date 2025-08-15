<?php

namespace App\Mail;

use App\Models\Examination;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExamReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Examination $exam;
    public string $recipientType; // 'invigilator' or 'student'

    public function __construct(Examination $exam, string $recipientType = 'student')
    {
        $this->exam = $exam;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        $subject = $this->recipientType === 'invigilator'
            ? 'Examination Invigilation Reminder'
            : 'Examination Reminder';

        return $this->subject($subject)
            ->view('emails.exam_reminder')
            ->with([
                'exam' => $this->exam,
                'recipientType' => $this->recipientType,
            ]);
    }
}


