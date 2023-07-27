<?php

namespace App\Repositories;

use App\Interfaces\CompanyRepositoryInterface;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function getAll()
    {
        return Company::paginate(10);
    }
    public function getById($id)
    {
        return Company::findOrFail($id);
    }
    public function delete($id)
    {
        Company::destroy($id);
    }
    public function create(array $data)
    {
        if($data['thumbnail']){
            $path = Storage::putFile('images/company', $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Company::create($data);
    }
    public function update($id, array $data)
    {
        return Company::whereId($id)->update($data);
    }
    
}