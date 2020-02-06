<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $table='location';
    public function Car()
    {
      return $this->belongsTo('App\Car');

    }
    public function Parking()
    {
      return $this->hasMany('App\Parking');

    }
}
