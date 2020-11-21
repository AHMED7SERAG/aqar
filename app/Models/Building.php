<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use SoftDeletes;
    protected $table ='buildings';	
    protected $fillable = [
      'city' ,'district','suk_number','suk_date','land_area','building_area' ,'address','latitude','interfaces_number','longitude', 'benefits_nearby','price' ,'commission','commission_value','price_after_commission','garage_floor','street_type','street_name','user_id','deleted_at','floors_number','apartments_number','driver_room_number'
    ];
    protected $hidden = [
        'created_at',
        'updated_at','pivot'
    ];
    public function scopeSelection( $query)
    {
        return $query->select('id', 'city' ,'district','suk_number','suk_date','land_area','building_area' ,'address','latitude','interfaces_number',
        'longitude', 'benefits_nearby','price' ,'commission','commission_value','price_after_commission','garage_floor','street_type'
        ,'street_name','user_id','floors_number','apartments_number','driver_room_number');
    }
    public function user()
    {
       return $this->belongsTo('\App\User','user_id');
    }
    public function gallery()
    {
       return $this->hasMany('\App\Models\Gallery','aqar_id');
    }
}
