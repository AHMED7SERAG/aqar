<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Admin extends  Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $fillable = [
        'name','email', 'password','mobile '
    ];
    protected $hidden = [
        'password','created_at','updated_at'
    ];
    public function scopeSelection( $query)
    {
        return $query->select('id','name','email','mobile');
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
