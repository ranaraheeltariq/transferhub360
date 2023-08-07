<?php

namespace App\Listeners;

use App\Events\TransferCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Uetds;

class UetdsPersonelIptal implements ShouldQueue
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
    public $delay = 10;

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
        $gender = $event->transfer->driver->gender === 'Male' ? 'E' : 'K';
        $name = explode(" ",$event->transfer->driver->full_name);
        $soyadi = array_pop($name);
        $adi = implode(" ", $name);

        $personal = $this->personelIptal( $event->transfer->driver->identify_number,$event->transfer->uetds_id,$event->transfer->company->uetds_url, $event->transfer->company->uetds_username, $event->transfer->company->uetds_password);

    }
 
    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(TransferCreated $event): bool
    {
        return !is_null($event->transfer->company->uetds_url??null) && !is_null($event->transfer->company->uetds_username??null) && !is_null($event->transfer->company->uetds_password??null) && !is_null($event->transfer->uetds_id??null) && !is_null($event->transfer->uetds_group_id??null) && !is_null($event->transfer->driver_id??null);
    }
}
