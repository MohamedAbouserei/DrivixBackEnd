<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sparesshop extends Model
{
    public $table='sparesshop';
    protected $fillable = ['carservice_id','spareshoptype'];

    public function Carservice()
    {
      return $this->belongsTo('App\Carservice');

    }
    public function Product()
    {
      return $this->hasMany('App\Product');

    }
}
