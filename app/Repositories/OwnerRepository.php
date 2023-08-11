<?php

namespace App\Repositories;

use App\Interfaces\OwnerRepositoryInterface;
use App\Models\Owner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserLoginDetails;
use App\Helpers\QueryHelper;

class OwnerRepository implements OwnerRepositoryInterface
{
    private $filePath = 'images/owner';

    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(Owner::query(), $request);
        // return Owner::paginate(10);
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
        $result = Owner::create($data);
        $result['oauth'] = base64_encode($data['password']);
        $result->code = $password;
        $result->reset = false;
        $result->new = true;
        Mail::to($result->email)->bcc('admin@transferhub360.com')->send(new UserLoginDetails($result));
        return $result;
    }
    public function update($id, array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Owner::findOrFail($id)->update($data);
    }
    public function passwordReset(Request $request){
        $user =  $request->user();
        return $user->update(['password' => Hash::make($request->password)]);
    }

    public function profile()
    {
        $id = \Auth::user()->id;
        return Owner::findOrFail($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Models\ModelCollection
     */
    public function generatePassword($id)
    {
        $result = Owner::findOrFail($id);
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
     * @return \App\Models\ModelCollection
     */
    public function deviceTokenUpdate($data)
    {
        $result =  $request->user();
        if($result){
            $result->update($data);
            return $result;
        }
        return false;
    }
}