<?php

namespace App\Repositories;

use App\Interfaces\TransferRepositoryInterface;
use App\Models\Transfer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;
use App\Events\TransferCreated;
use App\Events\TransferSeferGrupCreated;
use App\Events\TransferDriverAssigned;
use App\Events\TransferDriverUnassigned;
use App\Events\TransferUpdated;
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
        if($result) {
            $result = Transfer::find($result->id); //Get Latest Created Data
            // Check This Provider have UETDS login Information
            if(!is_null($result->company->uetds_url??null) && !is_null($result->company->uetds_username??null) && !is_null($result->company->uetds_password??null))
            {
                TransferCreated::dispatch($result);
                TransferSeferGrupCreated::dispatch($result);
                if(!empty($data['driver_id'])){
                    TransferDriverAssigned::dispatch($result);
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
                        TransferDelete::dispatch($transfer);
                        TransferCreated::dispatch($transfer);
                        TransferSeferGrupCreated::dispatch($transfer);
                        if(!is_null($transfer->driver_id??null)){
                            TransferDriverAssigned::dispatch($transfer);
                        }
                        PassangerAttached::dispatch($transfer);
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
            else {
                $transfer->passengers()->attach($request->passenger_id); 
            }
            if($transfer->passengers()->exists()){
                if(!is_null($transfer->uetds_id)){
                    PassangerAttached::dispatch($transfer);
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
            file_put_contents(public_path('SEFER_'.$id.'.pdf'),$uetdsFile);
            $path = Storage::putFile('file/transfer', public_path('SEFER_'.$id.'.pdf'));
            $data['file_path'] = $path;
            $update = $transfers[0]->update($data);
            unlink(public_path('SEFER_'.$id.'.pdf'));
            $result = (['file_url' => 'https://transferhub360.s3.amazonaws.com/'.$path]);
            return $result;
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