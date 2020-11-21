<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppSetting extends Model
{
    use SoftDeletes;
    protected $table ='app_settings';	
    protected $fillable = [
        'app_name',	'logo',	'app_version'
    ];
    protected $hidden = [
        'created_at',
        'updated_at','pivot','deleted_at'
    ];
    public function scopeSelection( $query)
    {
        return $query->select('id', 'app_name','logo','app_version');
    }
    public function getLogoAttribute($val )
    {
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
}
