<?php

namespace App\Events;

use App\Models\Examination;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class InvigilatorsAllocated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The examination instance.
     */
    public Examination $examination;

    /**
     * The collection of assigned invigilator users.
     */
    public Collection $invigilators;

    public function __construct(Examination $examination, Collection $invigilators)
    {
        $this->examination = $examination;
        $this->invigilators = $invigilators;
    }
}


