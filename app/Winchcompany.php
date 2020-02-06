<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Winchcompany extends Model
{
    public $table='winchcompany';
    protected $fillable = ['role_id','company_type'];

    public function Role()
    {
      return $this->belongsTo('App\Role');

    }
    public function Winchdriver()
    {
      return $this->hasMany('App\Winchdriver');

    }
    public function Winchcompanybranches()
    {
      return $this->hasMany('App\Winchcompanybranches');

    }
}
