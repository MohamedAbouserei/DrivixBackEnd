<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carserviceoffers extends Model
{
    public $table='carserviceoffers';
    public function Carservice()
    {
      return $this->belongsTo('App\Carservice');

    }
}
