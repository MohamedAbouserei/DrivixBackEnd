<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Winchdriver extends Model
{
    public $table='winchdriver';
    protected $fillable = ['role_id','winchcompany_id','availability','price_per_km'];

    public function Role()
    {
      return $this->belongsTo('App\Role');

    }
    public function Winchcompany()
    {
      return $this->belongsTo('App\Winchcompany');

    }
    public function Order()
    {
      return $this->hasMany('App\Order','winchdriver_id','id');

    }

}
