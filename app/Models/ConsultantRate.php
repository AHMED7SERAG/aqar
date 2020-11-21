<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ConsultantRate extends Model
{
    protected $table = 'consultants_rates';
    protected $fillable = [
      'consultant_id','user_id','execution_speed','execution_quality','explanation_clarification',	'permanent_presence','effective_communication','comment'
    ];  
    protected $hidden = [
      'updated_at','pivot'
   ];
   
    public function getCreatedAtAttribute($val)
    {
      
       return ($val !==null) ?  Carbon::parse($val)->diffForHumans(['options' => 0]): $val; 
    }
    public function user()
    {
       return $this->belongsTo('\App\User','user_id');
    }
    public function consultant()
    {
       return $this->belongsTo('\App\Models\Consultant','consultant_id');
    }
}
