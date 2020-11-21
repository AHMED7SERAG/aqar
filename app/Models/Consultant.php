<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Consultant extends Authenticatable implements JWTSubject
{
    use Notifiable; 
    use  SoftDeletes;
    protected $table ='consultants';		
    protected $fillable = [
        'username','name','email','password','mobile','email_verified','block','company_id','picture','deleted_at','execution_speed','execution_quality','explanation_clarification',	'permanent_presence','effective_communication','total_rate'
    ];
    protected $hidden = [
        'password', 'remember_token','email_verified' ,'block','is_owner','created_at','updated_at','deleted_at'
    ];
    
    public function scopeSelection( $query)
    {
        return $query->select('id','username','name','email','mobile','picture','company_id');
    }
    public function getPictureAttribute($val )
    {
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
    // public  function company ()
    // {
    //    return $this->belongsTo('\App\Models\Company','company_id');
    // }
    public  function rates ()
    {
       return $this->hasMany('\App\Models\ConsultantRate');
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