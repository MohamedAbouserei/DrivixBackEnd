<?php

    function getMyUnseenMails () {
        $myMails = \App\Admin_mails::where('to_id' , \Illuminate\Support\Facades\Auth::user()->id)->where('seen' , '0')->get();
        return $myMails;
    }
    function getmyMails () {
        $myMails = \App\Admin_mails::where('to_id' , \Illuminate\Support\Facades\Auth::user()->id)->limit(5)->get();
        return $myMails;
    }

    function getUserImage ($id) {
        $profile = \App\Profile::where('User_id' , $id)->first();
        if($profile) {
            return 'http://www.drivixcorp.com/api/storage/' . $profile->image . '/users';
        }
        else {
            return 'http://www.drivixcorp.com/api/storage/default.png/users';
        }
    }

?>
