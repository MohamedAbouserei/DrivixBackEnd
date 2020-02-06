<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $table='product';
    public function Sparesshop()
    {
      return $this->belongsTo('App\Sparesshop');

    }
    public function Productimg()
    {
      return $this->hasMany('App\Productimg');

    }

}
