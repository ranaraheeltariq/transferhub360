<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as ResetPassword;
use Illuminate\Support\Facades\Auth;

class Driver extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, ResetPassword;
    protected $guard = 'drivers';
    protected $dates = ['deleted_at'];

    // *********************** START PARENT CLASS *****************************
    
    /**
    * Get the company associated with the Driver.
    */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // *********************** END PARENT CLASS *******************************

    // *********************** START CHILD CLASS *****************************

        /**
         * Get the Transfer associated with the Driver.
         */
        public function transfers()
        {
            return $this->hasMany(Transfer::class);
        }

        /**
         * Get the Vehicle associated with the Driver.
         */
        public function vehicles()
        {
            return $this->hasMany(Vehicle::class);
        }


    // *********************** END CHILD CLASS *******************************

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
     * Send reset password token to user after verification of username
     *
     * @return string Thumbnail url
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\MailResetPasswordNotification($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identify_number',
        'full_name',
        'contact_number',
        'email',
        'password',
        'thumbnail',
        'address',
        'gender',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
