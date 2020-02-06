<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serviceprovider extends Model
{
    public $table='serviceprovider';
    protected $fillable = ['User_id'];

    public function User()
    {
      return $this->belongsTo('App\User','User_id','id');

    }
    public function Contactus()
    {
      return $this->hasMany('App\Contactus');

    }
    public function Role()
    {
      return $this->hasMany('App\Role');

    }

}
