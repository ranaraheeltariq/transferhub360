<?php

namespace App\Repositories;

use App\Interfaces\PassengerRepositoryInterface;
use App\Models\Passenger;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\QueryHelper;

class PassengerRepository implements PassengerRepositoryInterface
{
    private $filePath = 'images/passenger';

    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(Passenger::query(), $request);
        // return Passenger::paginate(10);
    }
    public function getById($id)
    {
        return Passenger::findOrFail($id);
    }
    public function delete($id)
    {
        return Passenger::destroy($id);
    }
    public function create(array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        $password = $data['password'];
        $data['password'] = Hash::make($password);
        return Passenger::create($data);
    }
    public function update($id, array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        
        return Passenger::findOrFail($id)->update($data);
    }
    public function passwordReset(Request $request){
        $user =  $request->user();
        return $user->update(['password' => Hash::make($request->password)]);
    }
    
}