<?php

namespace App\Listeners;

use App\Events\TransferNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Supervisor;
use App\Traits\PushNotification;

class SupervisorNotification
{
    use PushNotification;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransferNotification $event): void
    {
        $transfer = $event->transfer;
        $this->sendNotification('Transfer', $transfer->id, __('response_messages.notification.supervisorTitle'), __('response_messages.notification.supervisorBody', ['pickup' => $transfer->pickup_location, 'dropof' => $transfer->dropoff_location]),Supervisor::whereNotNull('device_token')->pluck('device_token')->toArray());
    }
}
