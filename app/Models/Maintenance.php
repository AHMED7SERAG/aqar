<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintenance extends Model
{
    use  SoftDeletes;
    protected $table ='maintenance';															
    protected $fillable = [
        'date', 'time','type' ,'main_user','user_id','deleted_at'
    ];
    protected $hidden = [
        'created_at','updated_at','pivot','deleted_at'
    ];
    public function scopeSelection($query)
    {
        return $query->select('id','date', 'time','type' ,'picture','main_user','user_id','deleted_at');
    }
    public function user()
    {
       return $this->belongsTo('\App\User','user_id');
    }
    public function pictures()
    {
       return $this->hasMany('\App\Models\MaintenancePicture','maintenance_id');
    }
    public function getPictureAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
}
