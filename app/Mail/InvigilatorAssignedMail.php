<?php

namespace App\Mail;

use App\Models\Examination;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvigilatorAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Examination $exam;

    public function __construct(Examination $exam)
    {
        $this->exam = $exam;
    }

    public function build()
    {
        return $this->subject('Invigilation Assignment Notification')
            ->view('emails.invigilator_assigned')
            ->with([
                'exam' => $this->exam,
            ]);
    }
}


