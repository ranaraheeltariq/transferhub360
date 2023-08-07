<?php

namespace App\Listeners;

use App\Events\TransferCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Uetds;

class UetdsSeferGrupEkle implements ShouldQueue
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
    public $delay = 60;

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
        $group = $this->seferGrupEkle($event->transfer->uetds_id, $event->transfer->pickup_location.' '.$event->transfer->dropoff_location, 'Transfer from '.$event->transfer->pickup_location.' to '.$event->transfer->dropoff_location, 'TR', $event->transfer->pickup_city_code, $event->transfer->pickup_zone_code, $event->transfer->pickup_location, 'TR', $event->transfer->dropoff_city_code, $event->transfer->dropoff_zone_code, $event->transfer->dropoff_location, '0',$event->transfer->company->uetds_url, $event->transfer->company->uetds_username, $event->transfer->company->uetds_password);
        $event->transfer->update(['uetds_group_id' => $group]);
    }
 
    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(TransferCreated $event): bool
    {
        return !is_null($event->transfer->company->uetds_url??null) && !is_null($event->transfer->company->uetds_username??null) && !is_null($event->transfer->company->uetds_password??null) && !is_null($event->transfer->uetds_id??null);
    }
}
