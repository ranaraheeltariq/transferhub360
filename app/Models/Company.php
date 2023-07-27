<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    // *********************** START PARENT CLASS *****************************


    // *********************** END PARENT CLASS *******************************

    // *********************** START CHILD CLASS *****************************

        /**
         * Get the Customer associated with the Company.
         */
        public function customers()
        {
            return $this->hasMany(Customer::class);
        }

        /**
         * Get the Driver associated with the Company.
         */
        public function drivers()
        {
            return $this->hasMany(Driver::class);
        }

        /**
         * Get the Passenger associated with the Company.
         */
        public function passengers()
        {
            return $this->hasMany(Passenger::class);
        }

        /**
         * Get the Supervisor associated with the Company.
         */
        public function supervisors()
        {
            return $this->hasMany(Supervisor::class);
        }

        /**
         * Get the Transfer associated with the Company.
         */
        public function transfers()
        {
            return $this->hasMany(Transfer::class);
        }

        /**
         * Get the Transfer Detail associated with the Company.
         */
        public function transferDetails()
        {
            return $this->hasMany(TransferDetail::class);
        }

        /**
         * Get the Vehicle associated with the Company.
         */
        public function vehicles()
        {
            return $this->hasMany(Vehicle::class);
        }

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
            $user = Auth::user();
            $model->created_user_name = $user->full_name;
            $model->updated_user_name = $user->full_name;
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
     * @return string Thumbnail url
     */
    public function getThumbnailAttribute()
    {
        if($this->attributes['thumbnail'] != null){
            return Storage::url($this->attributes['thumbnail']);
        }
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];    
}
