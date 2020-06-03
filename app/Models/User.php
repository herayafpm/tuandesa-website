<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use App\Authorizable;

use App\Notifications\EmailVerificationNotification;
use App\Notifications\PasswordResetNotification;


class User extends Authenticatable
{
    use HasRoles,HasApiTokens, Notifiable,Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','username', 'email', 'password','ttl','address','no_hp','photo','last_login','fcm_token','email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        // static::updating(function (User $user) {
        //     if ($user->isDirty('email')) {
        //         $user->email_verified_at = null;
        //         $user->sendEmailVerificationNotification();
        //     }
        // });
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerificationNotification());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])
        ->translatedFormat('d F Y H:i:s');
    }

    public function getUpdatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['updated_at'])
        ->diffForHumans();
    }
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }
    public function aduans()
    {
        return $this->hasMany('App\Models\Aduan');
    }
    public function pelayanans()
    {
        return $this->hasMany('App\Models\Pelayanan');
    }
    // public function bantuans()
    // {
    //     return $this->hasMany('App\Models\Bantuan');
    // }
}
