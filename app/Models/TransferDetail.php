<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TransferDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    // *********************** START PARENT CLASS *****************************
    
    /**
    * Get the company associated with the Transfer Detail.
    */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the Transfer associated with the Transfer Detail.
     */
    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }

    // *********************** END PARENT CLASS *******************************

    // *********************** START CHILD CLASS ******************************

    // *********************** END CHILD CLASS *******************************

    /**
     * The getter for assign complete url to storage files
     *
     * @return string File Path url
     */
    public function getFilePathAttribute()
    {
        if($this->attributes['file_path'] != null){
            return Storage::url($this->attributes['file_path']);
        }
    }

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
            $model->company_id = $user->company_id;
            if(!isset($model->created_user_name)){
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
