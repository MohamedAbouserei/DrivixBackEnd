<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    public $table='workshop';
    protected $fillable = ['carservice_id'];

    public function Carservice()
    {
      return $this->belongsTo('App\Carservice');

    }
    public function Workshoptype()
    {
      return $this->hasMany('App\Workshoptype');

    }
}
