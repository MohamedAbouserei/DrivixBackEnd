<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commentlikes extends Model
{
    public $table='commentlikes';
    public function User()
    {
      return $this->belongsTo('App\User','User_id','id');

    }
    public function Comment()
    {
      return $this->belongsTo('App\Comment');

    }
}
