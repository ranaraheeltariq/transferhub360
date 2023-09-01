<?php

namespace App\Listeners;

use App\Events\TransferNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\PushNotification;

class DriverNotification
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
        if($transfer->driver()->exists()){
            $this->sendNotification('Transfer', $transfer->id, __('response_messages.notification.driverTitle'), __('response_messages.notification.driverBody', ['pickup' => $transfer->pickup_location, 'dropof' => $transfer->dropoff_location]), [$transfer->driver->device_token]);
        }
    }
}
