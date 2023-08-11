<?php

namespace App\Listeners;

use App\Events\TransferCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Uetds;

class UetdsSeferEkle implements ShouldQueue
{
    use InteractsWithQueue, Uetds;
 
    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    // public $connection = 'sqs';
 
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    // public $queue = 'listeners';
 
    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 05;

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
        $starttime = strtotime($event->transfer->pickup_time);
        $starttime = date('H:i', $starttime);
        $endtime = strtotime($event->transfer->pickup_time) + 60*60;
        $endtime = date('H:i', $endtime);
        $number_plate = $event->transfer->vehicle_id != null ? $event->transfer->vehicle->number_plate : "34 ABC 111";
        $uetds = $this->seferEkle($number_plate, $event->transfer->pickup_date, $starttime, $event->transfer->info, $event->transfer->id, $event->transfer->pickup_date, $endtime,$event->transfer->company->uetds_url, $event->transfer->company->uetds_username,$event->transfer->company->uetds_password);
        $event->transfer->update(['uetds_id' => $uetds->uetdsSeferReferansNo]);
    }
 
    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(TransferCreated $event): bool
    {
        return !is_null($event->transfer->company->uetds_url??null) && !is_null($event->transfer->company->uetds_username??null) && !is_null($event->transfer->company->uetds_password??null) && is_null($event->transfer->uetds_id??null);
    }
}
