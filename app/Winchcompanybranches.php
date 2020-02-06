<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Winchcompanybranches extends Model
{
    public $table='winchcompanybranches';
    protected $fillable = ['winchcompany_id','phone','address'];

    public function Winchcompany()
    {
      return $this->belongsTo('App\Winchcompany');

    }
}
