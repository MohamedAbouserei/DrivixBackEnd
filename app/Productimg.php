<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productimg extends Model
{
    public $table='productimg';

    public function Product()
    {
      return $this->belongsTo('App\Product');

    }
}
