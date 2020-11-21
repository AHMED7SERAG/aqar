<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite  extends Model
{
    protected $table = 'favorite_salon';
    protected $fillable = [
        'user_id', 'salon_id'
    ];
   
    public function scopeSelection( $query)
    {
        return $query->select('id','user_id','salon_id');
    }
  
    public function salons()
    {
       return $this->belongsTo('\App\Models\Salon','salon_id');
    }
    public function user()
    {
       return $this->belongsTo('\App\User','user_id');
    }
}
