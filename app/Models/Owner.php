<?php

namespace App\Models;

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

class Owner extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, ResetPassword;
    protected $guard = 'owners';
    protected $dates = ['deleted_at'];

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
        'full_name',
        'contact_number',
        'email',
        'password',
        'thumbnail',
        'device_token',
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
