<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class UetdsCity extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    // *********************** START PARENT CLASS *****************************


    // *********************** END PARENT CLASS *******************************

    // *********************** START CHILD CLASS *****************************

    // *********************** END CHILD CLASS *******************************

    /**
     * The attributes that set on creation and updation.
     *
     * @var array
     */
    public static function boot(){
        parent::boot();
        static::creating(function($model)
        {
            if(!isset($model->created_user_name)){
                $user = Auth::user();
                $model->created_user_name = $user->full_name;
                $model->updated_user_name = $user->full_name;
            }
        });
        static::updating(function($model)
        {
            if(!isset($model->created_user_name)){
                $user = Auth::user();
                $model->created_user_name = $user->full_name;
                $model->updated_user_name = $user->full_name;
            }
        });
        static::deleting(function($model)
        {
            if(!isset($model->created_user_name)){
                $user = Auth::user();
                $model->updated_user_name = $user->full_name;
                $model->save();
            }
        });
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
