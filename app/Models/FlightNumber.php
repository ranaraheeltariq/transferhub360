<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FlightNumber extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    // *********************** START PARENT CLASS *****************************

        /**
         * Get the Company associated with the report.
         */
        public function company()
        {
            return $this->belongsTo(Company::class);
        }


    // *********************** END PARENT CLASS *******************************

    // *********************** START CHILD CLASS *****************************

        /**
         * Get the Transfer associated with the Customer.
         */
        public function transfers()
        {
            return $this->hasMany(Transfer::class);
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
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
