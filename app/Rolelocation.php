<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rolelocation extends Model
{
    public $table='rolelocation';
    public $fillable = array('role_id', 'location', 'long','lat');

    public function Role()
    {
      return $this->belongsTo('App\Role');

    }
}
