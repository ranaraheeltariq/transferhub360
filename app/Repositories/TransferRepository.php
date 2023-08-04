<?php

namespace App\Repositories;

use App\Interfaces\TransferRepositoryInterface;
use App\Models\Transfer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;
use App\Events\TransferCreated;

class TransferRepository implements TransferRepositoryInterface
{
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
        return Transfer::destroy($id);
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
        return QueryHelper::applyFilterOrderLimitPagination(Transfer::query(), $request);
    }

}