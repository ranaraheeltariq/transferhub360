<?php

namespace App\Repositories;

use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function getAll()
    {
        return Customer::paginate(10);
    }
    public function getById($id)
    {
        return Customer::findOrFail($id);
    }
    public function delete($id)
    {
        return Customer::destroy($id);
    }
    public function create(array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile('images/customer', $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Customer::create($data);
    }
    public function update($id, array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile('images/customer', $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Customer::whereId($id)->update($data);
    }
    
}