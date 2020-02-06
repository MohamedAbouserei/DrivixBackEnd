<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public $table='admin';
    protected $fillable = ['id','user_id' , 'national_id' , 'created_at' , 'updated_at'];
    public function User()
    {
      return $this->belongsTo('App\User');

    }
    public function Supervisor()
    {
      return $this->hasMany('App\Supervisor');

    }
    public function Mangercontact()
    {
      return $this->hasMany('App\Mangercontact');

    }
    public function Sitesettings()
    {
      return $this->hasOne('App\Sitesettings');

    }

}
