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
    
    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(Transfer::query(), $request, ['passengers']);
        // return Transfer::paginate(10);
    }
    public function getById($id)
    {
        return Transfer::findOrFail($id);
    }
    public function delete($id)
    {
        $transfer = Transfer::destroy($id);
        TransferDelete::dispatch($transfer);
        return $transfer;
    }
    public function create(array $data)
    {
        $transfer = Transfer::create($data);
        TransferCreated::dispatch($transfer);
        return $transfer;
    }
    
    public function update($id, array $data)
    {
        if(!empty($data['assigneVehicle']) && $data['assigneVehicle'] == true){
            $data['vehicle_assigned_by'] = $request->user()->full_name;
            $data['vehicle_assigned_time'] = date('Y-m-d h:i:s');
        }
        if(!empty($data['unassignedVehicle']) && $data['unassignedVehicle'] == true){
            $data['vehicle_id'] = NULL;
            $data['driver_id'] = NULL;
            $data['vehicle_assigned_by'] = NULL;
            $data['vehicle_assigned_time'] = NULL;
            $data['patient_approving_status'] = NULL;
        }
        $transfer = Transfer::whereId($id)->update($data);
        TransferCreated::dispatch($transfer);
        return $transfer;
    }

    public function myTransfers(Request $request)
    {
        $request->merge(['relational_column' => 'driver_id', 'relational_id' => $request->user()->id]);
        return QueryHelper::applyFilterOrderLimitPagination(Transfer::query(), $request, ['passengers']);
    }

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
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
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

}