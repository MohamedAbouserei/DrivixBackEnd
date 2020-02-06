<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gasstation_supervisormanage extends Model
{
    public $table='gasstation_supervisormanager';
    public function Gassstation()
    {
      return $this->belongsTo('App\Gasstation');

    }
    public function Supervisor()
    {
      return $this->belongsTo('App\Supervisor');

    }
}
