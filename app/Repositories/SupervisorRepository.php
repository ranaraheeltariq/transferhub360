<?php

namespace App\Repositories;

use App\Interfaces\SupervisorRepositoryInterface;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\QueryHelper;

class SupervisorRepository implements SupervisorRepositoryInterface
{
    private $filePath = 'images/supervisor';

    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(Supervisor::query(), $request);
        // return Supervisor::paginate(10);
    }
    public function getById($id)
    {
        return Supervisor::findOrFail($id);
    }
    public function delete($id)
    {
        return Supervisor::destroy($id);
    }
    public function create(array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        $password = $data['password'];
        $data['password'] = Hash::make($password);
        return Supervisor::create($data);
    }
    public function update($id, array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        return Supervisor::whereId($id)->update($data);
    }
    public function passwordReset(Request $request){
        $user =  $request->user();
        return $user->update(['password' => Hash::make($request->password)]);
    }
    
}