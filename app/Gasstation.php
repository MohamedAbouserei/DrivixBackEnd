<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gasstation extends Model
{
    public $table='gasstation';
    public $timestamps = true;
    protected $fillable = ['id', 'name', 'tier_repare', 'blowing_air', 'petrol_80', 'petrol_92', 'petrol_95', 'align_wheel', 'sollar', 'gas', 'car_washing', 'blowing_nitro', 'fix_suspension', 'oil_change', 'lat', 'long', 'address', 'google_rate', 'icon', 'created_at' , 'city'];

    public function Gassstation_supervisormanage()
    {
      return $this->belongsToMany('App\Gassstation_supervisormanage');

    }
}
