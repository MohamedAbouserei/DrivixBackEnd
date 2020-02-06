<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $table='role';
    protected $fillable = ['id' , 'serviceprovider_id','supervisor_id','type','status','work_from','work_to','name','description','lock','workingdays'];

    public function Supervisor()
    {
      return $this->belongsTo('App\Supervisor');

    }
    public function Serviceprovider()
    {
      return $this->belongsTo('App\Serviceprovider');

    }
    public function Carservice()
    {
      return $this->hasOne('App\Carservice');

    }
    public function Winchcompany()
    {
      return $this->hasOne('App\Winchcompany');

    }
    public function Winchdriver()
    {
      return $this->hasOne('App\Winchdriver');

    }
    public function Roleimgs()
    {
      return $this->hasMany('App\Roleimgs' , 'role_id' , 'id');

    }
    public function Rolephone()
    {
      return $this->hasMany('App\Rolephone');

    }
    public function Rolelocation()
    {
      return $this->hasMany('App\Rolelocation');

    }
}
