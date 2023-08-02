<?php

namespace App\Listeners;

use App\Events\CancelAssignedVehicle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UetdsPersonelIptal
{
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
    public function handle(CancelAssignedVehicle $event): void
    {
        //
    }
}
