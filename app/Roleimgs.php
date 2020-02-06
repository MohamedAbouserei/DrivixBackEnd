<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roleimgs extends Model
{
    public $table='roleimgs';
    protected $fillable = ['role_id','date','image','type','status'];

    public function Role()
    {
      return $this->belongsTo('App\Role');

    }

}
