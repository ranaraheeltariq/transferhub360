<?php

namespace App\Repositories;

use App\Interfaces\TransferRepositoryInterface;
use App\Models\Transfer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;
use App\Events\TransferCreated;
use App\Events\TransferDelete;
use App\Events\PassangerAttached;
use App\Traits\Uetds;
use App\Traits\ModelHelper;
use App\Traits\PushNotification;

class TransferRepository implements TransferRepositoryInterface
{
    use Uetds,ModelHelper,PushNotification;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return collection $ModelCollection
     */
    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(Transfer::query(), $request, ['passengers', 'vehicle','driver','customer']);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer  $id
     * @return collection $ModelCollection
     */
    public function getById($id)
    {
        // return Transfer::findOrFail($id);
        return Transfer::with(['passengers', 'vehicle','driver','customer'])->findOrFail($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer  $id
     * @return collection $ModelCollection
     */
    public function delete($id)
    {
        $transfer = Transfer::findOrFail($id);
        if($transfer){
            // TransferDelete::dispatch($transfer);
            if($transfer->uetds_id && $transfer->company->uetds_url && $transfer->company->uetds_username && $transfer->company->uetds_password)
            {
                // delete sefer
                $uetds = $this->seferIptal($transfer->uetds_id,$transfer->company->uetds_url, $transfer->company->uetds_username,$transfer->company->uetds_password);
                if($uetds === 0)
                {
                    $transfer->update(['uetds_id' => null]);
                    $result = $transfer->destroy($id);
                }
            }
            return $result;
        }
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array  $data
     * @return collection $ModelCollection
     */
    public function create(array $data)
    {
        $result = Transfer::create($data);
        // TransferCreated::dispatch($transfer);
        if($result) {
            $result = Transfer::find($result->id); //Get Latest Created Data
            // Check This Provider have UETDS login Information
            if(!is_null($result->company->uetds_url??null) && !is_null($result->company->uetds_username??null) && !is_null($result->company->uetds_password??null))
            {
                $starttime = strtotime($result->pickup_time);
                $starttime = date('H:i', $starttime);
                $endtime = strtotime($result->pickup_time) + 60*60;
                $endtime = date('H:i', $endtime);
                $number_plate = $result->vehicle_id ? $result->vehicle->number_plate : "34 ABC 111";
                $uetds = $this->seferEkle($number_plate, $result->pickup_date, $starttime, $result->info, $result->id, $result->pickup_date, $endtime,$result->company->uetds_url, $result->company->uetds_username, $result->company->uetds_password);

                // If Sefer Created Successfully then Create Group and Update Group Number
                if($uetds->sonucKodu === 0){
                    $group = $this->seferGrupEkle($uetds->uetdsSeferReferansNo, $result->pickup_location.' '.$result->dropoff_location, 'Transfer from '.$result->pickup_location.' to '.$result->dropoff_location, 'TR', $result->pickup_city_code, $result->pickup_zone_code, $result->pickup_location, 'TR', $result->dropoff_city_code, $result->dropoff_zone_code, $result->dropoff_location, '0',$result->company->uetds_url, $result->company->uetds_username, $result->company->uetds_password);

                    $update = $result->update(['uetds_id' => $uetds->uetdsSeferReferansNo,'uetds_group_id' => $group]);
                    $result->uetds = $uetds->uetdsSeferReferansNo;
                    $result->group = $group;
                    // If UETDS Id and Group Number Update also we have Driver Id in Request then We Create Personel Ekle
                    if($update && !is_null($request->driver_id??null)){
                        $gender = $result->driver->gender === 'Male' ? 'E' : 'K';
                        $name = explode(" ",$result->driver->full_name);
                        $soyadi = array_pop($name);
                        $adi = implode(" ", $name);

                        $personal = $this->personelEkle($uetds->uetdsSeferReferansNo,0, 'TR', $result->driver->identify_number,$gender,$adi,$soyadi,$result->driver->contact_number,$result->company->uetds_url, $result->company->uetds_username, $result->company->uetds_password);

                        $result->uetdsPersonal = $personal;
                    }
                }
                else {
                    $result->uetds = $uetds; //If seferekle have any error display the error
                }
            }
            if(!empty($result->driver_id)){
                if($this->getFcmTokenByDriver($result->driver_id)){
                    $result->notification =  $this->sendNotification('Transfer', $result->id, 'Transfer Created', $result, [$this->getFcmTokenByDriver($result->driver_id)]);
                 }
            }
            return $result;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  array  $data
     * @param int $id
     * @return collection $ModelCollection
     */
    public function update($id, array $data)
    {
        $transfer = Transfer::findOrFail($id);
        if($transfer){
            if(!empty($data['assigneVehicle']) && $data['assigneVehicle'] == true){
                $data['vehicle_assigned_by'] = \Auth::user()->full_name;
                $data['vehicle_assigned_time'] = date('Y-m-d h:i:s');
                $data['status'] = 'YOLCULUK BAŞLAMADI';
                unset($data['assigneVehicle']);
            }
            if(!empty($data['unassignedVehicle']) && $data['unassignedVehicle'] == true){
                $data['vehicle_id'] = NULL;
                $data['driver_id'] = NULL;
                $data['vehicle_assigned_by'] = NULL;
                $data['vehicle_assigned_time'] = NULL;
                $data['status'] = 'ARAÇ ATANMADI';
                unset($data['unassignedVehicle']);
            }
            if(!empty($data['startTransfer']) && $data['startTransfer'] == true){
                unset($data['startTransfer']);
                $data['status'] = 'YOLCULUK DEVAM EDİYOR';
            }
            if(!empty($data['stopTransfer']) && $data['stopTransfer'] == true){
                unset($data['stopTransfer']);
                $data['status'] = 'YOLCULUK TAMAMLANDI';
            }
            $result = $transfer->update($data);
            // TransferCreated::dispatch($transfer);
            if($result){
                if(!is_null($transfer->company->uetds_url??null) && !is_null($transfer->company->uetds_username??null) &&!is_null( $transfer->company->uetds_password??null)){
                    // Update UETDS Sefer Data if Vehicle_id, Pickup Date and Time changed
                    if($transfer->uetds_id){
                        $starttime = strtotime($transfer->pickup_time);
                        $starttime = date('H:i', $starttime);
                        $endtime = strtotime($transfer->pickup_time) + 60*60;
                        $endtime = date('H:i', $endtime);
                        $number_plate = $transfer->vehicle_id ? $transfer->vehicle->number_plate : "34 ABC 111";
                        $uetds = $this->seferGuncelle($transfer->uetds_id, $number_plate, $transfer->pickup_date, $starttime, $transfer->info, $transfer->id, $transfer->pickup_date, $endtime,$transfer->company->uetds_url, $transfer->company->uetds_username, $transfer->company->uetds_password);
                        
                        if(!is_null($transfer->driver_id??null)){
                            // Delete Current UETDS Personal Data
                            $personal = $this->personelIptal($transfer->driver->identify_number, $transfer->uetds_id,$transfer->company->uetds_url, $transfer->company->uetds_username, $transfer->company->uetds_password);
                            // Create New UETDS Personal Data
                            $gender = $transfer->driver->gender === 'Male' ? 'E' : 'K';
                            $name = explode(" ",$transfer->driver->full_name);
                            $soyadi = array_pop($name);
                            $adi = implode(" ", $name);
                            $personal = $this->personelEkle($transfer->uetds_id,0, 'TR', $transfer->driver->identify_number,$gender,$adi,$soyadi,$transfer->driver->contact_number,$transfer->company->uetds_url, $transfer->company->uetds_username, $transfer->company->uetds_password);
                            $transfer->uetdsPersonal = $personal === 0 ?  'UETDS Personal Information Successfully Created' : 'UETDS Personal Information have error';
                        }
                    }
                    elseif(!$transfer->uetds_id) {
                        // If UETDS_id Not Store Create New Sefer
                        $starttime = strtotime($transfer->pickup_time);
                        $starttime = date('H:i', $starttime);
                        $endtime = strtotime($transfer->pickup_time) + 60*60;
                        $endtime = date('H:i', $endtime);
                        $number_plate = $transfer->vehicle_id ? $transfer->vehicle->number_plate : "34 ABC 111";
                        $uetds = $this->seferEkle($number_plate, $transfer->pickup_date, $starttime, $transfer->info, $transfer->id, $transfer->pickup_date, $endtime,$transfer->company->uetds_url, $transfer->company->uetds_username, $transfer->company->uetds_password);
                        if($uetds->sonucKodu === 0){
                            // If Sefer Successfully Create then Create Sefer Group
                            $group = $this->seferGrupEkle($uetds->uetdsSeferReferansNo, $transfer->pickup_location.' '.$transfer->dropoff_location, 'Transfer from '.$transfer->pickup_location.' to '.$transfer->dropoff_location, 'TR', $transfer->pickup_city, $transfer->pickup_zone, $transfer->pickup_location, 'TR', $transfer->dropoff_city, $transfer->dropoff_zone, $transfer->dropoff_location, '0',$transfer->company->uetds_url,$transfer->company->uetds_username,$transfer->company->uetds_password);

                            $update = $transfer->update(['uetds_id' => $uetds->uetdsSeferReferansNo,'uetds_group_id' => $group]);
                            // if sefer and sefer group updated on db and have driver id then create personel ekle
                            if($update && !is_null($transfer->driver_id??null)){
                                $transfer->uetds = $uetds->uetdsSeferReferansNo;
                                $gender = $transfer->driver->gender === 'Male' ? 'E' : 'K';
                                $name = explode(" ",$transfer->driver->full_name);
                                $soyadi = array_pop($name);
                                $adi = implode(" ", $name);
                                $personal = $this->personelEkle($uetds->uetdsSeferReferansNo,0, 'TR', $transfer->driver->identify_number,$gender,$adi,$soyadi,$transfer->driver->contact_number,$transfer->company->uetds_url, $transfer->company->uetds_username, $transfer->company->uetds_password);
                                $transfer->uetdsPersonal = $personal === 0 ?  'UETDS Personal Information Successfully Created' : 'UETDS Personal Information have error';
                            }
                        }
                        else {
                            $transfer->uetds = $uetds;
                        }
                    }
                }
                if($this->getFcmTokenByDriver($transfer->driver_id)){
                    $transfer->notification =   $this->sendNotification('Transfer', $transfer->id, 'Transfer Created', $transfer, [$this->getFcmTokenByDriver($transfer->driver_id)]);
                }
            }
            return $transfer;
        }
        return false;
    }

    /**
     * Get List of Transfer for Login Driver.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return collection $ModelCollection
     */
    public function myTransfers(Request $request)
    {
        $request->merge(['relational_column' => 'driver_id', 'relational_id' => $request->user()->id]);
        return QueryHelper::applyFilterOrderLimitPagination(Transfer::query(), $request, ['passengers', 'vehicle','driver','customer']);
    }

    /**
     * Attached Passenger with Transfer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return collection $ModelCollection
     */
    public function attachPassengers(Request $request)
    {
        $transfer = Transfer::findOrFail($request->transfer_id);
        if($transfer){
            if($transfer->passengers()->exists()){
                $transfer->passengers()->sync($request->passenger_id);
            }
            $transfer->passengers()->attach($request->passenger_id); 
            // PassangerAttached::dispatch($transfer);
            if($transfer->passengers()->exists()){
                if(!is_null($transfer->uetds_id)){

                    foreach($event->transfer->passengers as $passenger){
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
            }
            return $transfer->passengers;
        }
        return false;
    }

    /**
     * UETDS transfer PDF.
     *
     * @param  $id
     * @return string $path
     */
    public function uetdsPdf($id)
    {
        $transfers = Transfer::where('uetds_id', $id)->get();
        if($transfers){
            if($transfers[0]->file_path){
                $s3path = (explode('/',$transfers[0]->file_path));
                $oldfile = array_pop($s3path);
                $delete = Storage::delete('file/transfer/'.$oldfile);
            }
            $uetdsFile = $this->seferDetayCiktisiAl($transfers[0]->uetds_id,$transfers[0]->company->uetds_url, $transfers[0]->company->uetds_username, $transfers[0]->company->uetds_password);
            $path = Storage::putFile('file/transfer', public_path('SEFER_'.$id.'.pdf'));
            $data['file_path'] = $path;
            $update = $transfers[0]->update($data);
            unlink(public_path('SEFER_'.$id.'.pdf'));
            $data = (['file_path' => 'https://transferhub360.s3.amazonaws.com/'.$path]);
            return $data;
        }
        return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string  $date
     * @param integer $id
     * @return collection $ModelCollection
     */
    public function groupByType($date,$id)
    {
        $transfers = Transfer::selectRaw('count(id) as transfer_count, type')->where('pickup_date', $date)->groupBy('type');
        if($id != null){
            $transfers = $transfers->where('driver_id',$id);
        }
        $transfers = $transfers->get();
        return $transfers;
    }

    /**
     * Display a listing of the resource.
     *
     * @param integer $id
     * @return collection $ModelCollection
     */
    public function passenger($id)
    {
        $transfer = Transfer::findOrFail($id);
        if($transfer){
            return $transfer->passengers;
        }
        return false;
    }
}