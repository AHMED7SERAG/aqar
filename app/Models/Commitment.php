<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commitment extends Model
{
    use SoftDeletes;
    protected $table ='commitments';	
    protected $fillable = [
        'hand_commitment','monthly_amount',	'remaining_months',	'finance_application_id','deleted_at'
    ];
    protected $hidden = [
        'created_at',
        'updated_at','pivot','deleted_at'
    ];
    public function scopeSelection( $query)
    {
        return $query->select('id','hand_commitment','monthly_amount','remaining_months','finance_application_id');
    }
   
    public function gallery()
    {
       return $this->hasMany('\App\Models\Gallery','finance_application_id');
    }
}
