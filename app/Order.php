<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table='order';
    public function User()
    {
      return $this->belongsTo('App\User');

    }
    public function Winchdriver()
    {
      return $this->belongsTo('App\Winchdriver');

    }
    public function Feedback()
    {
      return $this->hasOne('App\Feedback');

    }

}
