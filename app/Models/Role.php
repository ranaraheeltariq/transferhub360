<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Support\Facades\Auth;
use App\Models\Scopes\CompanyScope;

class Role extends SpatieRole
{
    /**
     * The attributes that set on creation and updation.
     *
     * @var array
     */
    public static function boot(){
        static::addGlobalScope(new CompanyScope);
        parent::boot();
        static::creating(function($model)
        {
            $user = Auth::user();
            if(!isset($model->company_id)){
                $model->company_id = $user->company_id;
            }
        });
    }

    // /**
    //  * The "booted" method of the model.
    //  */
    // protected static function booted(): void
    // {
    //     static::addGlobalScope(new CompanyScope);
    // }
}
