<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin_mails;
Use Exception;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Supervisor;
use Carbon\Carbon;
use File;
use Yajra\Datatables\Datatables;
use Response;
use App\User;
use App\Admin;
use Illuminate\Support\Facades\Session;

class EmailController extends Controller
{
    //
    public function mymailsCms () {
        return view('mail.index');
    }
    public function mymailsCmsAjax () {
        $mails= Admin_mails::where('to_id' , Auth::user()->id )->orWhere('from_id' , Auth::user()->id)->get();
        $AllMails = array();
        $x = 0;
        foreach ($mails as $mail) {
            // check date first if set

            $created_at = new Carbon($mail->created_at);
            $date = new \DateTime($created_at);
            $created_at = $date->format('m/d/Y');

            $AllMails[$x]['id'] = $mail->id;

            $AllMails[$x]['from_mail'] = $mail->from_user->email;

            if ( $mail->from_user->name == Auth::user()->name) {
                $AllMails[$x]['from_name'] = '(From You )';
                $AllMails[$x]['seen'] = '1';
            } else {
                $AllMails[$x]['from_name'] = $mail->from_user->name;
                $AllMails[$x]['seen'] = $mail->seen;
            }
            $AllMails[$x]['title'] = $mail->title;
            $AllMails[$x]['date'] = $created_at;
            $x++;
        }
        $data = collect($AllMails);
        return Datatables::of($data)->setRowClass(function($p) {
            return $p['seen'] == '0' ? 'not-shown' : '';
        })->make(true);
    }
    public function getMailCmsAjax ($id) {
        $mail = Admin_mails::find($id);
        if(isset($mail)) {
            return view('mail.show' , compact('mail'));
        }
        else {
            Session::flash('warning','this Mail is not exists any more !!');
            return redirect()->route('mymailsCms');
        }
    }
    public function changeMailStatusCmsAjax ($id) {
        $mail = Admin_mails::find($id);
        if(isset($mail)) {
            $mail->seen = 1 ;
            $mail->save();
            return 'true';
        }
        else {
           return 'false';
        }
    }
    public function AddMailCms () {
        $sup_ad_users = User::where('type' , '1')->orWhere('type' , '0')->get();
        return view('mail.add' , compact('sup_ad_users'));
    }
    public function StoreMail ( Request $request) {
        if(Auth::user()) {
            $request->validate([
                "email_to" => "required" ,
                "title" => "required|string|min:5|max:100" ,
                "message" => 'required|string|min:20|max:700' ,
            ]);
            // save email
            $admin_mail = new Admin_mails();
            $admin_mail->from_id = Auth::user()->id ;
            $admin_mail->to_id = $request->email_to ;
            $admin_mail->title = $request->title ;
            $admin_mail->message = $request->message ;
            $admin_mail->save();
            Session::flash('success','your Mail Send successfully');
            return redirect()->route('mymailsCms');
        }
        else {
            Session::flash('warning','Can\'t  send mail right now');
            return redirect()->route('mymailsCms');
        }
    }
}
