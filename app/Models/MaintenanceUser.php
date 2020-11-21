<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceUser extends Model
{
    use  SoftDeletes;
    protected $table ='maintenance_users';															
    protected $fillable = [
        'name', 'mobile','user_id','deleted_at'
    ];
    protected $hidden = [
        'created_at','updated_at','pivot','deleted_at'
    ];
    public function scopeSelection($query)
    {
        return $query->select('id','name', 'mobile','user_id','deleted_at');
    }
    public function user()
    {
       return $this->belongsTo('\App\User','user_id');
    }
}
