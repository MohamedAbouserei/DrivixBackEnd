<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    public $table='estimate';
    public function User()
    {
      return $this->belongsTo('App\User' , 'User_id' , 'id');

    }
    public function Carservice()
    {
      return $this->belongsTo('App\carservice');

    }
}
