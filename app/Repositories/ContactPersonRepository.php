<?php

namespace App\Repositories;

use App\Interfaces\ContactPersonRepositoryInterface;
use App\Models\ContactPerson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;

class ContactPersonRepository implements ContactPersonRepositoryInterface
{
    private $filePath = 'images/contactperson';

    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(ContactPerson::query(), $request);
        // return Customer::paginate(10);
    }
    public function getById($id)
    {
        return ContactPerson::findOrFail($id);
    }
    public function delete($id)
    {
        return ContactPerson::destroy($id);
    }
    public function create(array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return ContactPerson::create($data);
    }
    public function update($id, array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return ContactPerson::findOrFail($id)->update($data);
    }
    
}