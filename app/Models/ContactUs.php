<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
  
    use SoftDeletes;
    protected $table ='contact_us';	
    protected $fillable = [
        'name','mobile','details'
    ];
    protected $hidden = [
        'created_at',
        'updated_at','pivot','deleted_at'
    ];
    public function scopeSelection( $query)
    {
        return $query->select('id','name','mobile','details');
    }
}
