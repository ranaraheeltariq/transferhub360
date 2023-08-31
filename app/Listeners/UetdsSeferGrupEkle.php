<?php

namespace App\Listeners;

use App\Events\TransferSeferGrupCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Uetds;

class UetdsSeferGrupEkle
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
    public function handle(TransferSeferGrupCreated $event): void
    {
        $transfer = $event->transfer;
        $group = $this->seferGrupEkle($transfer->uetds_id, $transfer->pickup_location.' '.$transfer->dropoff_location, 'Transfer from '.$transfer->pickup_location.' to '.$transfer->dropoff_location, 'TR', $transfer->pickup_city_code, $transfer->pickup_zone_code, $transfer->pickup_location, 'TR', $transfer->dropoff_city_code, $transfer->dropoff_zone_code, $transfer->dropoff_location, '0',$transfer->company->uetds_url, $transfer->company->uetds_username, $transfer->company->uetds_password);
        $transfer->update(['uetds_group_id' => $group]);
    }
 
    // /**
    //  * Determine whether the listener should be queued.
    //  */
    // public function shouldQueue(TransferSeferGrupCreated $event): bool
    // {
    //     $transfer = $event->transfer;
    //     return !is_null($transfer->company->uetds_url??null) && !is_null($transfer->company->uetds_username??null) && !is_null($transfer->company->uetds_password??null) && !is_null($transfer->uetds_id??null);
    // }
}
