<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    public $table='supervisor';
    public function User()
    {
      return $this->belongsTo('App\User' , 'User_id' , 'id');

    }
    public function Admin()
    {
      return $this->belongsTo('App\Admin');

    }
    public function Mangercontact()
    {
      return $this->hasMany('App\Mangercontact');

    }
    public function Gassstation_supervisormanage()
    {
      return $this->hasMany('App\Gassstation_supervisormanage');

    }
    public function Role()
    {
      return $this->hasMany('App\Role');

    }
    public function Contactus()
    {
      return $this->hasMany('App\Contactus');

    }

}
