<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $fillable = [
        'username', 'email', 'password','mobile','email_verified' ,'block','is_owner'
    ];
    protected $hidden = [
        'password', 'remember_token','email_verified' ,'block','is_owner','created_at','updated_at'
    ];
    
    public function scopeSelection( $query)
    {
        return $query->select('id','name', 'email','mobile');
    }
    public  function locations ()
    {
       return $this->hasMany('\App\Models\UserLocation');
    }
    public  function card ()
    {
       return $this->hasMany('\App\Models\Card');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
