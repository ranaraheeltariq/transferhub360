<?php

namespace App\Repositories;

use App\Interfaces\HotelRepositoryInterface;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;

class HotelRepository implements HotelRepositoryInterface
{
    private $filePath = 'images/customer';

    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(Hotel::query(), $request);
        // return Customer::paginate(10);
    }
    public function getById($id)
    {
        return Hotel::findOrFail($id);
    }
    public function delete($id)
    {
        return Hotel::destroy($id);
    }
    public function create(array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Hotel::create($data);
    }
    public function update($id, array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Hotel::findOrFail($id)->update($data);
    }
    
}