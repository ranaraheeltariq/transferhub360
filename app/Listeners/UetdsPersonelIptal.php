<?php

namespace App\Listeners;

use App\Events\TransferDriverUnassigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Uetds;

class UetdsPersonelIptal
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
    public function handle(TransferDriverUnassigned $event): void
    {
        $transfer = $event->transfer;
        $gender = $transfer->driver->gender === 'Male' ? 'E' : 'K';
        $name = explode(" ",$transfer->driver->full_name);
        $soyadi = array_pop($name);
        $adi = implode(" ", $name);

        $personal = $this->personelIptal( $transfer->driver->identify_number,$transfer->uetds_id,$transfer->company->uetds_url, $transfer->company->uetds_username, $transfer->company->uetds_password);

    }
 
    // /**
    //  * Determine whether the listener should be queued.
    //  */
    // public function shouldQueue(TransferDriverUnassigned $event): bool
    // {
    //     $transfer = $event->transfer;
    //     return !is_null($transfer->company->uetds_url??null) && !is_null($transfer->company->uetds_username??null) && !is_null($transfer->company->uetds_password??null) && !is_null($transfer->uetds_id??null) && !is_null($transfer->uetds_group_id??null) && !is_null($transfer->driver_id??null);
    // }
}
