<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends  Model
{    
    
    protected $table = 'temporary_otp';
    protected $fillable = [
        'email', 'code' ,'receiver_id'
    ];
   
    public function scopeSelection( $query)
    {
        return $query->select('id','receiver_id','email','code');
    }
  
    
}
