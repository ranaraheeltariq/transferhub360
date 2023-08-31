<?php

namespace App\Listeners;

use App\Events\PassangerAttached;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\Uetds;

class YolcuEkle
{
    use Uetds;

    /**
     * Handle the event.
     */
    public function handle(PassangerAttached $event): void
    {
        $transfer = $event->transfer;
        foreach($transfer->passengers as $passenger){
            $adi = $passenger->first_name;
            $soyadi = $passenger->last_name;
            $cinsiyet = $passenger->gender == 'Male' ? 'E' : 'K';
            $passport = $passenger->id_number;

            $uetds = $this->yolcuEkle($transfer->uetds_id, $transfer->uetds_group_id, $passenger->country_code, $passport, $adi, $soyadi, $cinsiyet,$transfer->company->uetds_url, $transfer->company->uetds_username, $transfer->company->uetds_password);
            if(!is_null($uetds->sonucKodu)&&$uetds->sonucKodu === 0){
                $transfer->passengers()->updateExistingPivot($passenger->id,['uetds_ref_no' => $uetds->uetdsYolcuRefNo]);
            }
        }
    }
 
    // /**
    //  * Determine whether the listener should be queued.
    //  */
    // public function shouldQueue(PassangerAttached $event): bool
    // {
    //     $transfer = $event->transfer;
    //     return !is_null($transfer->company->uetds_url??null) && !is_null($transfer->company->uetds_username??null) && !is_null($transfer->company->uetds_password??null) && !is_null($transfer->uetds_id??null) && !is_null($transfer->uetds_group_id??null);
    // }
}
