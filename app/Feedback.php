<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    public $table='feedback';
    public function Order()
    {
      return $this->belongsTo('App\Order');

    }
}
