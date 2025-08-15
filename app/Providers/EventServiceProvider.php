<?php

namespace App\Providers;

use App\Events\ExamNotification;
use App\Events\InvigilatorsAllocated;
use App\Listeners\SendExamNotificationEmails;
use App\Listeners\SendInvigilatorsAllocatedEmails;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        InvigilatorsAllocated::class => [
            SendInvigilatorsAllocatedEmails::class,
        ],
        ExamNotification::class => [
            SendExamNotificationEmails::class,
        ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}


