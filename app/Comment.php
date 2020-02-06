<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $table='comment';
    public function User()
    {
      return $this->belongsTo('App\User','User_id','id');

    }
    public function Commentlikes()
    {
      return $this->hasMany('App\Commentlikes');

    }
    public function replay()
    {
      return $this->hasMany('App\Comment');

    }
    public function Carservice()
    {
      return $this->belongsTo('App\Carservice');

    }

}
