<?php

namespace App\Repositories;

use App\Interfaces\FlightNumberRepositoryInterface;
use App\Models\FlightNumber;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;

class FlightNumberRepository implements FlightNumberRepositoryInterface
{

    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(FlightNumber::query(), $request);
    }
    public function getById($id)
    {
        return FlightNumber::findOrFail($id);
    }
    public function delete($id)
    {
        return FlightNumber::destroy($id);
    }
    public function create(array $data)
    {
        return FlightNumber::create($data);
    }
    public function update($id, array $data)
    {
        $result = FlightNumber::findOrFail($id);
        if($result)
        {
            return $result->update($data);
        }
        return false;
    }
    
}