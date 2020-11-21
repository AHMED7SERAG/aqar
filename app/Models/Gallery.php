<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table ='gallery';
    protected $fillable = [
        'aqar_id', 'photo','aqar_type'
    ];
    protected $hidden = [
        'created_at',
        'updated_at','pivot'
    ];
    public function scopeSelection( $query)
    {
        return $query->select('id','aqar_id', 'photo','aqar_type');
    }
     public function getPhotoAttribute($val )
    {
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
    public function aqar()
    {
       return $this->belongsTo('\App\Models\Aqar','aqar_id');
    }
}
