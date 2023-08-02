<?php

namespace App\Listeners;

use App\Events\AssigneVehicle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UetdsPersonelEkle
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
    public function handle(AssigneVehicle $event): void
    {
        //
    }
}
