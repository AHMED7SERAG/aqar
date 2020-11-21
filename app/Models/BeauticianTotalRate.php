<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeauticianTotalRate extends Model
{
    protected $table = 'beautician_total_rate';
    protected $fillable = [
         'value','beautician_id',
       
    ];
    
    public function scopeSelection( $query)
    {
        return $query->select('id','value','beautician_id');
    }
   
    public function beautician()
    {
       return $this->belongsTo('\App\Models\Beautician','beautician_id');
    }
}
