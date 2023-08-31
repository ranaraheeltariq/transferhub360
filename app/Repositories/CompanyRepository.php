<?php

namespace App\Repositories;

use App\Interfaces\CompanyRepositoryInterface;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserLoginDetails;
use App\Helpers\QueryHelper;

class CompanyRepository implements CompanyRepositoryInterface
{
    private $filePath = 'images/company';

    public function getAll(Request $request)
    {
        return QueryHelper::applyFilterOrderLimitPagination(Company::query(), $request);
    }
    public function getById($id)
    {
        return Company::findOrFail($id);
    }
    public function delete($id)
    {
        return Company::destroy($id);
    }
    public function create(array $data)
    {
        if(!empty($data['thumbnail'])){
            $path = Storage::putFile($this->filePath, $data['thumbnail']);
            $data['thumbnail'] = $path;
        }
        $result = Company::create($data);
        if($result){
            $password = Str::random(10); 
            $data['password'] = Hash::make($password);
            $resultUser = $result->users()->create([
                'company_id'    => $result->id,
                'full_name' => $data['contact_person'],
                'email' => $data['email'],
                'contact_number' => $data['contact_person_no'],
                'password'  => $data['password'],
            ]);
            $resultUser->code = $password;
            $resultUser->reset = false;
            $resultUser->new = true;
             Mail::to($resultUser->email)->bcc('admin@transferhub360.com')->send(new UserLoginDetails($resultUser));
             $resultUser->roles()->sync(Role::withoutGlobalScope(\App\Models\Scopes\CompanyScope::class)->where(['company_id' => $result->id, 'name' => 'super admin'])->get()->pluck('id')->toArray());
        }
        return $result;
    }
    public function update($id, array $data)
    {
        $company = Company::findOrFail($id);
        if($company){
            if(!empty($data['thumbnail'])){
                $path = Storage::putFile($this->filePath, $data['thumbnail']);
                $data['thumbnail'] = $path;
            }
            $result = $company->update($data);
            return $company;
        }
        return false;
    }
    
}