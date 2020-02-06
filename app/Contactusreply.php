<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contactusreply extends Model
{
    public $table='contactusreply';
    public function Contactus()
    {
      return $this->belongsTo('App\Serviceprovider');

    }
}
