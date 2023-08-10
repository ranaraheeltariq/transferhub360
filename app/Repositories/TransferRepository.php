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

class TransferRepository implements TransferRepositoryInterface
{
    use Uetds;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return collection $ModelCollection
     */
    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(Transfer::query(), $request, ['passengers']);
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
        return Transfer::with('passengers')->findOrFail($id);
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
            $result = $transfer->destroy($id);
            TransferDelete::dispatch($transfer);
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
        $transfer = Transfer::create($data);
        TransferCreated::dispatch($transfer);
        return $transfer;
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
            }
            if(!empty($data['unassignedVehicle']) && $data['unassignedVehicle'] == true){
                $data['vehicle_id'] = NULL;
                $data['driver_id'] = NULL;
                $data['vehicle_assigned_by'] = NULL;
                $data['vehicle_assigned_time'] = NULL;
                $data['patient_approving_status'] = NULL;
            }
            if(!empty($data['startTransfer']) && $data['startTransfer'] == true){

                $data['status'] = 'YOLCULUK DEVAM EDİYOR';
            }
            if(!empty($data['stopTransfer']) && $data['stopTransfer'] == true){

                $data['status'] = 'YOLCULUK TAMAMLANDI';
            }
            $result = $transfer->update($data);
            TransferCreated::dispatch($transfer);
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
        return QueryHelper::applyFilterOrderLimitPagination(Transfer::query(), $request, ['passengers']);
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
            PassangerAttached::dispatch($transfer);
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
            return $path;
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