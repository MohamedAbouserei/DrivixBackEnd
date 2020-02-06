<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mangercontact extends Model
{
    public $table='mangercontact';
    public function Admin()
    {
      return $this->belongsTo('App\Admin');

    }
    public function Supervisor()
    {
      return $this->belongsTo('App\Supervisor');

    }
}
