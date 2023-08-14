<?php

namespace App\Repositories;

use App\Interfaces\DriverRepositoryInterface;
use App\Models\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserLoginDetails;
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
        $result->code = $password;
        $result->reset = false;
        $result->new = true;
        Mail::to($result->email)->bcc('admin@transferhub360.com')->send(new UserLoginDetails($result));
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
        $result = Driver::findOrFail($id);
        if($result){
            $password = Str::random(10); 
            $data['password'] = Hash::make($password);
            $update = $result->update($data);
            $result['oauth'] = base64_encode($data['password']);
            $result->code = $password;
            $result->reset = true;
            $result->new = false;
            Mail::to($result->email)->bcc('admin@transferhub360.com')->send(new UserLoginDetails($result));
            return $result;
        }
        return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return collection $ModelCollection
     */
    public function count()
    {
        $driver = Driver::count();
        return $driver;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Models\ModelCollection
     */
    public function deviceTokenUpdate($data)
    {
        $result =  \Auth::user();
        if($result){
            $result->update($data);
            return $result;
        }
        return false;
    }
}