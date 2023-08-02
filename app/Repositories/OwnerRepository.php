<?php

namespace App\Repositories;

use App\Interfaces\OwnerRepositoryInterface;
use App\Models\Owner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OwnerRepository implements OwnerRepositoryInterface
{
    private $filePath = 'images/owner';

    public function getAll()
    {
        return Owner::paginate(10);
    }
    public function getById($id)
    {
        return Owner::findOrFail($id);
    }
    public function delete($id)
    {
        return Owner::destroy($id);
    }
    public function create(array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        $password = $data['password'];
        $data['password'] = Hash::make($password);
        return Owner::create($data);
    }
    public function update($id, array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Owner::whereId($id)->update($data);
    }
    public function passwordReset(Request $request){
        $user =  $request->user();
        return $user->update(['password' => Hash::make($request->password)]);
    }
    
}