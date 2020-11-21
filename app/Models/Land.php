<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Land extends Model
{
    use SoftDeletes;
    protected $table ='lands';								
    protected $fillable = [
        'city', 'district','suk_number' ,'suk_date','address','longitude','latitude','area','street_type','street_name',
        'street_view' ,'interfaces_number','meter_price','price','commission','commission_value','price_after_commission','user_id'
    ];
    protected $hidden = [
        'created_at','updated_at','pivot','deleted_at'
    ];
    public function scopeSelection($query)
    {
        return $query->select('id','city','district','suk_number' ,'suk_date','address','longitude','latitude','area','street_type',
        'street_view' ,'interfaces_number','meter_price','price','commission','commission_value','price_after_commission','user_id');
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
