<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceApplication extends Model
{
    use SoftDeletes;
    protected $table ='finance_application';	
    protected $fillable = [
        'full_name','bank_salary','salary','total_salary','deduction','employer','occupation','service_length',	'remain_service_life','user_id','deleted_at'
    ];
    protected $hidden = [
        'created_at',
        'updated_at','pivot','deleted_at'
    ];
    public function scopeSelection( $query)
    {
        return $query->select('id','full_name','bank_salary','salary','total_salary','deduction','employer','occupation','service_length','remain_service_life','user_id');
    }
    public function user()
    {
       return $this->belongsTo('\App\User','user_id');
    }
    public function commitment()
    {
       return $this->hasMany('\App\Models\Commitment','finance_application_id');
    }
}
