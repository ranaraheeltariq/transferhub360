<?php

namespace App\Repositories;

use App\Interfaces\UetdsCityRepositoryInterface;
use App\Models\UetdsCity;
use Illuminate\Support\Facades\Storage;

class UetdsCityRepository implements UetdsCityRepositoryInterface
{
    public function getAll()
    {
        return UetdsCity::paginate(10);
    }
    public function getById($id)
    {
        return UetdsCity::findOrFail($id);
    }
    public function delete($id)
    {
        return UetdsCity::destroy($id);
    }
    public function create(array $data)
    {
        return UetdsCity::create($data);
    }
    public function update($id, array $data)
    {
        return UetdsCity::whereId($id)->update($data);
    }
    
}