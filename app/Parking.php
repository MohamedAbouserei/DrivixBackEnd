<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    public $table='parking';
    public function Location()
    {
      return $this->belongsTo('App\Location');

    }
}
