<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Notification extends Controller
{
    public function sendNotification($token, $msg){
        return event(new App\Events\sendNotification('Someone has added u to group'));
    }
}
