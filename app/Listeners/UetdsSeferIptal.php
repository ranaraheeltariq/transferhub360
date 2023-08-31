<?php

namespace App\Listeners;

use App\Events\TransferDelete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Uetds;

class UetdsSeferIptal
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
    public function handle(TransferDelete $event): void
    {
        $transfer = $event->transfer;
        $uetds = $this->seferIptal($transfer->uetds_id, $transfer->company->uetds_url, $transfer->company->uetds_username,$transfer->company->uetds_password);
        if($uetds === 0){
            $transfer->update(['uetds_id' => null]);
        }
    }
 
    // /**
    //  * Determine whether the listener should be queued.
    //  */
    // public function shouldQueue(TransferDelete $event): bool
    // {
    //     $transfer = $event->transfer;
    //     return !is_null($transfer->company->uetds_url??null) && !is_null($transfer->company->uetds_username??null) && !is_null($transfer->company->uetds_password??null) && !is_null($transfer->uetds_id??null) && !is_null($transfer->uetds_group_id??null);
    // }
}
