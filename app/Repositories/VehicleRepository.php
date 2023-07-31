<?php

namespace App\Repositories;

use App\Interfaces\VehicleRepositoryInterface;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Storage;

class VehicleRepository implements VehicleRepositoryInterface
{
    public function getAll()
    {
        return Vehicle::paginate(10);
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
            $path = Storage::putFile('images/vehicle', $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Vehicle::create($data);
    }
    public function update($id, array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile('images/vehicle', $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Vehicle::whereId($id)->update($data);
    }
    
}