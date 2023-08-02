<?php

namespace App\Repositories;

use App\Interfaces\DriverRepositoryInterface;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverRepository implements DriverRepositoryInterface
{
    private $filePath = 'images/driver';

    public function getAll()
    {
        return Driver::paginate(10);
    }
    public function getById($id)
    {
        return Driver::findOrFail($id);
    }
    public function delete($id)
    {
        return Driver::destroy($id);
    }
    public function create(array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        $password = $data['password'];
        $data['password'] = Hash::make($password);
        return Driver::create($data);
    }
    public function update($id, array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Driver::whereId($id)->update($data);
    }
    public function passwordReset(Request $request){
        $user =  $request->user();
        return $user->update(['password' => Hash::make($request->password)]);
    }
    
}