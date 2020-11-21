<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Villa extends Model
{
    use SoftDeletes;
    protected $table ='villas';
    protected $fillable = [
        'city',	'district','suk_number','suk_date','address','longitude','latitude','land_area','building_area'	,'interfaces_number','street_type','street_name','floors_number','bedrooms','bathrooms','halls_number',	'session_rooms','kitchens',	'maid_room'	,'driver_room','indoor_parking','benefits_nearby','price','commission','commission_value','price_after_commission','user_id','deleted_at'
    ];
    protected $hidden = [
        'created_at',
        'updated_at','pivot','deleted_at'
    ];
    public function scopeSelection( $query)
    {
        return $query->select('id','city',	'district','suk_number','suk_date','address','longitude','latitude','land_area','building_area'	,'interfaces_number','street_type','street_name','floors_number','bedrooms','bathrooms','halls_number','session_rooms','kitchens','maid_room','benefits_nearby','driver_room','indoor_parking','price','commission','commission_value','price_after_commission','user_id');
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