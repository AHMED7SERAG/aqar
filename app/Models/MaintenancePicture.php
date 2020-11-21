<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenancePicture extends Model
{
    use SoftDeletes;
    protected $table ='maintenance_pictures';
    protected $fillable = [
        'maintenance_id', 'picture','deleted_at'
    ];
    protected $hidden = [
        'created_at','deleted_at',
        'updated_at','pivot'
    ];
    public function scopeSelection( $query)
    {
        return $query->select('id','maintenance_id', 'picture');
    }
     public function getPhotoAttribute($val )
    {
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
    public function maintenance()
    {
       return $this->belongsTo('\App\Models\Maintenance','maintenance_id');
    }
}
