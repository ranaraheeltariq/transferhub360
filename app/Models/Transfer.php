<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Transfer extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    // *********************** START PARENT CLASS *****************************
    
    /**
    * Get the company associated with the Transfer.
    */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the Passengers associated with the Transfer.
     */
    public function passengers()
    {
        return $this->belongsToMany(Passenger::class)->withPivot('uetds_ref_no');
    }
    
    /**
    * Get the Customer associated with the Transfer.
    */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    /**
    * Get the Driver associated with the Transfer.
    */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    
    /**
    * Get the Vehicle associated with the Transfer.
    */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }


    // *********************** END PARENT CLASS *******************************

    // *********************** START CHILD CLASS ******************************

        /**
         * Get the Transfer Detail associated with the Transfer.
         */
        public function transferDetail()
        {
            return $this->hasOne(TransferDetail::class);
        }

    // *********************** END CHILD CLASS *******************************

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
            $user = Auth::user();
            $model->updated_user_name = $user->full_name;
        });
        static::deleting(function($model)
        {
            $user = Auth::user();
            $model->updated_user_name = $user->full_name;
            $model->save();
        });
    }

    /**
     * The getter for assign complete url to storage files
     *
     * @return string file_path url
     */
    public function getFilePathAttribute()
    {
        if($this->attributes['file_path'] != null){
            return Storage::url($this->attributes['file_path']);
        }
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
