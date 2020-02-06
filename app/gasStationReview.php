<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gasStationReview extends Model
{
    //
    protected $table = 'gas_station_review';
    protected $fillable = ['id' , 'user_id' , 'gas_id' , 'rate' , 'created_at' , 'updated_at'];
}
