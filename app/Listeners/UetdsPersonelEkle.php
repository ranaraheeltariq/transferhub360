<?php

namespace App\Listeners;

use App\Events\TransferDriverAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Uetds;

class UetdsPersonelEkle
{
    use Uetds;

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
    public function handle(TransferDriverAssigned $event): void
    {
        $transfer = $event->transfer;
        $gender = $transfer->driver->gender === 'Male' ? 'E' : 'K';
        $name = explode(" ",$transfer->driver->full_name);
        $soyadi = array_pop($name);
        $adi = implode(" ", $name);

        $personal = $this->personelEkle($transfer->uetds_id,0, 'TR', $transfer->driver->identify_number,$gender,$adi,$soyadi,$transfer->driver->contact_number,$transfer->company->uetds_url, $transfer->company->uetds_username, $transfer->company->uetds_password);

    }
 
    // /**
    //  * Determine whether the listener should be queued.
    //  */
    // public function shouldQueue(TransferDriverAssigned $event): bool
    // {
    //     $transfer = $event->transfer;
    //     return !is_null($transfer->company->uetds_url??null) && !is_null($transfer->company->uetds_username??null) && !is_null($transfer->company->uetds_password??null) && !is_null($transfer->uetds_id??null) && !is_null($transfer->uetds_group_id??null) && !is_null($transfer->driver_id??null);
    // }
}
