<?php

namespace App\Listeners;

use App\Events\TransferDelete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Uetds;

class UetdsSeferIptal implements ShouldQueue
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
    public function handle(TransferDelete $event): void
    {
        $uetds = $this->seferIptal($event->transfer->uetds_id, $event->transfer->company->uetds_url, $event->transfer->company->uetds_username,$event->transfer->company->uetds_password);
        if($uetds === 0){
            $event->transfer->update(['uetds_id' => null]);
        }
    }
 
    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(TransferDelete $event): bool
    {
        return !is_null($event->transfer->company->uetds_url??null) && !is_null($event->transfer->company->uetds_username??null) && !is_null($event->transfer->company->uetds_password??null) && !is_null($event->transfer->uetds_id??null) && !is_null($event->transfer->uetds_group_id??null);
    }
}
