<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contactus extends Model
{
    public $table='contactus';
    public function Supervisor()
    {
      return $this->belongsTo('App\Supervisor');

    }
    public function Serviceprovider()
    {
      return $this->belongsTo('App\Serviceprovider');

    }
    public function Contactusreply()
    {
      return $this->hasMany('App\Contactusreply');

    }

}
