<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppSocial extends Model
{
    use SoftDeletes;
    protected $table ='app_social';	
    protected $fillable = [
        'name',	'logo',	'link'
    ];

    protected $hidden = [
        'created_at',
        'updated_at','pivot','deleted_at'
    ];
    public function scopeSelection( $query)
    {
        return $query->select('id', 'name',	'logo',	'link');
    }
    public function getLogoAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
}
