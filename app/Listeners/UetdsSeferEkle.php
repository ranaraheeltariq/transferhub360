<?php

namespace App\Listeners;

use App\Events\TransferCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Uetds;

class UetdsSeferEkle
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
    public function handle(TransferCreated $event): void
    {
        $transfer = $event->transfer;
        $starttime = strtotime($transfer->pickup_time);
        $starttime = date('H:i', $starttime);
        $endtime = strtotime($transfer->pickup_time) + 60*60;
        $endtime = date('H:i', $endtime);
        $number_plate = $transfer->vehicle_id != null ? $transfer->vehicle->number_plate : "34 ABC 111";
        $uetds = $this->seferEkle($number_plate, $transfer->pickup_date, $starttime, $transfer->info, $transfer->id, $transfer->pickup_date, $endtime,$transfer->company->uetds_url, $transfer->company->uetds_username,$transfer->company->uetds_password);
        $transfer->update(['uetds_id' => $uetds->uetdsSeferReferansNo]);
    }
 
    // /**
    //  * Determine whether the listener should be queued.
    //  */
    // public function shouldQueue(TransferCreated $event): bool
    // {
    //     $transfer = $event->transfer;
    //     return !is_null($transfer->company->uetds_url??null) && !is_null($transfer->company->uetds_username??null) && !is_null($transfer->company->uetds_password??null) && is_null($transfer->uetds_id??null);
    // }
}
