<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable

{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'token' , 'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token'
    ];


    ///////////////////////////our users
    public function Profile()
    {
      return $this->hasOne('App\Profile');

    }
    public function Supervisor()
    {
      return $this->hasOne('App\Supervisor');

    }
    public function Admin()
    {
      return $this->hasOne('App\Admin');

    }
    public function Serviceprovider()
    {
      return $this->hasOne('App\Serviceprovider');

    }
    /////the services that they have
    public function Estimate()
    {
      return $this->hasMany('App\Estimate');

    }
    public function Comment()
    {
      return $this->hasMany('App\Comment');

    }
    public function Commentlikes()
    {
      return $this->hasMany('App\Commentlikes');

    }
    public function Car()
    {
      return $this->hasMany('App\Car');

    }
    public function Order()
    {
      return $this->hasMany('App\Order');

    }

    // gas station review
    public function gas_review() {
        return $this->hasMany('App\gasStationReview' , 'user_id' , 'id');
    }
}
