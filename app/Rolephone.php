<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rolephone extends Model
{
    public $table='rolephone';
    public $fillable = array('role_id', 'phone');

    public function Role()
    {
      return $this->belongsTo('App\Role');

    }
}
