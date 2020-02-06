<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public $table='car';
    protected $fillable = ['User_id','year','color','model','brand'];

    public function User()
    {
      return $this->belongsTo('App\User');

    }
    public function Location()
    {
      return $this->hasMany('App\Location');

    }

}
