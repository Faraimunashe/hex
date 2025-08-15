<?php

namespace App\Events;

use App\Models\Examination;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExamNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The examination instance, including relations for recipients.
     */
    public Examination $examination;

    public function __construct(Examination $examination)
    {
        $this->examination = $examination;
    }
}


