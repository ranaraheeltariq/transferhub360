<?php

namespace App\Repositories;

use App\Interfaces\DriverRepositoryInterface;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Helpers\QueryHelper;

class DriverRepository implements DriverRepositoryInterface
{
    private $filePath = 'images/driver';

    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(Driver::query(), $request, ['vehicles']);
        // return Driver::paginate(10);
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
        $result = Driver::create($data);
        $result['oauth'] = base64_encode($data['password']);
        $result['password'] = $data['password'];
           // Mail::to($data['email'])->send(new UserLoginDetails($result));
        return $result;
    }
    public function update($id, array $data)
    {
        $driver = Driver::findOrFail($id);
        if($driver){
            if(!empty($data['thumbnail'])){
                $path = Storage::putFile($this->filePath, $data['thumbnail']);
                $data['thumbnail'] = $path;
            }
            $result = $driver->update($data);
            return $driver;
        }
        return false;
    }
    public function passwordReset(Request $request){
        $user =  $request->user();
        return $user->update(['password' => Hash::make($request->password)]);
    }

    public function profile()
    {
        $id = \Auth::user()->id;
        return Driver::findOrFail($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Models\ModelCollection
     */
    public function generatePassword($id)
    {
        $password = Str::random(10); 
        $data['password'] = Hash::make($password);
        $result = Driver::findOrFail($id)->update($data);
        $result['oauth'] = base64_encode($data['password']);
        $result['password'] = $data['password'];
           // Mail::to($data['email'])->send(new UserLoginDetails($result));
        return $result;
    }
}