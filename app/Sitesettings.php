<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sitesettings extends Model
{
    public $table='sitesettings';
    protected $fillable= ['id' , 'setting_name' , 'slug' , 'value' , 'status' , 'updated_at' , 'created_at'];
   }
