<?php

namespace App\Repositories;

use App\Interfaces\UetdsCityRepositoryInterface;
use App\Models\UetdsCity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;

class UetdsCityRepository implements UetdsCityRepositoryInterface
{
    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(UetdsCity::query(), $request);
    }
    public function getById($id)
    {
        return UetdsCity::findOrFail($id);
    }
    public function getByCityCode($city)
    {
        return UetdsCity::where('city_code', $city)->get();
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