<?php

namespace App\Repositories;

use App\Interfaces\VehicleRepositoryInterface;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;

class VehicleRepository implements VehicleRepositoryInterface
{
    private $filePath = 'images/vehicle';

    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(Vehicle::query(), $request);
        // return Vehicle::paginate(10);
    }
    public function getById($id)
    {
        return Vehicle::findOrFail($id);
    }
    public function delete($id)
    {
        return Vehicle::destroy($id);
    }
    public function create(array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Vehicle::create($data);
    }
    public function update($id, array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Vehicle::whereId($id)->update($data);
    }
    
}