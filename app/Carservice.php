<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carservice extends Model
{
    public $table='carservice';
    protected $fillable = ['role_id','servicetype','URL'];

    public function Comment()
    {
      return $this->hasMany('App\Comment');

    }
    public function Estimate()
    {
      return $this->hasMany('App\Estimate');

    }
    public function Sparesshop()
    {
      return $this->hasOne('App\Sparesshop');

    }
    public function Workshop()
    {
      return $this->hasOne('App\Workshop');

    }
    public function Role()
    {
      return $this->belongsTo('App\Role');

    }
    public function Carserviceoffers()
    {
      return $this->hasMany('App\Carserviceoffers');

    }

}
