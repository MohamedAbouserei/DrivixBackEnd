<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workshoptype extends Model
{
    public $table='workshoptype';
    protected $fillable = ['workshop_id','workshoptype'];

    public function Workshop()
    {
      return $this->belongsTo('App\Workshop');
    }
}
