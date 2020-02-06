<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin_mails extends Model
{
    //
    protected $table = 'admin_mails';
    protected $fillable = ['id' , 'from_id' , 'to_id' , 'title' , 'message' , 'seen'  ,'created_at' ,'updated_at'];

    function to_user () {
        return $this->belongsTo('App\User' , 'to_id' , 'id');
    }

    function from_user () {
        return $this->belongsTo('App\User' , 'from_id' , 'id');
    }

}
