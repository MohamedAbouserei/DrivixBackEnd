<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;
Use Exception;
use Validator;
use App\Supervisor;
use Carbon\Carbon;
use File;
use Yajra\Datatables\Datatables;
use Response;
use App\User;
use App\Product;
use Illuminate\Support\Facades\Session;
use Hash;

class SupervisorController extends Controller
{
    public function SupervisorsCms () {
        return view('supervisor.index');
    }

    public function getSupervisorsCms () {
        $AllDaTa= Supervisor::all();
        $AllSupervisors = array();
        $x = 0;
        foreach ($AllDaTa as $supervisor) {
            // check date first if set

            $AllSuperivsors[$x]['id'] = $supervisor->id;
            $AllSuperivsors[$x]['name'] = $supervisor->User->name;
            $AllSuperivsors[$x]['salary'] = $supervisor->salary;
            $AllSuperivsors[$x]['hiring'] = $supervisor->hire_date;
            $AllSuperivsors[$x]['work_hours'] = $supervisor->work_hours;
            $AllSuperivsors[$x]['status'] = $supervisor->User->status;
            $path = 'http://www.drivixcorp.com/api/storage/';
            if(isset($supervisor->User->Profile) && isset($supervisor->User->Profile->image)) {
                $response = $path .$supervisor->User->Profile->image.'/users';
                $AllSuperivsors[$x]['image'] = $response;
            } else {
                $response = $path .'default.png'.'/users';
                $AllSuperivsors[$x]['image'] = $response;
            }
            $x++;
        }
        $data = collect($AllSuperivsors);
        return Datatables::of($data)->setRowClass(function($p) {
            return $p['status'] == '0' ? 'locked-row' : 'unlocked-row';
        })->make(true);
    }

    public function lockAunlock (Request $request) {
        $p = Supervisor::find($request->id);
        if(isset($p)) {
            if($p->User->status == '1') {
                $p->status = '0';
                $p->User->status = '0';
            } else {
                $p->status = '1' ;
                $p->User->status = '1';
            }
            $p->User->save();
            $p->save();
            return 'true';
        }
        return 'false';
    }

    public function getSupervisorCms ($id) {
        $sup = Supervisor::find($id);
        if(isset($sup)) {
            return view('supervisor.show' , compact('sup'));
        }
        else {
            Session::flash('warning','this Supervisor is not exists any more !!');
            return redirect()->route('supervisors');
        }
    }

    public function updateSupervisorCms (Request $request) {
        $supervisor = Supervisor::find($request->id);
        if ($supervisor) {
            $request->validate([
                "id" => "required" ,
                "name" => "required|string" ,
                "email" => 'unique:users,email,'.$supervisor->User->id ,
                "npassowrd" => "nullable|string|min:6|max:16" ,
                "salary" => "numeric" ,
                "nationalID" => "numeric" ,
                "work_hours" => "integer" ,
                "hire_date" => "date" ,
                "image" => "max:2048"
            ]);
            // check image
            $imageName = $this->saveImage($request , 'users');
            if($imageName != false) {
                $supervisor->User->profile->image = $imageName;
                $supervisor->User->profile->save();
            }
            // save user
            $supervisor->User->name = $request->name;
            $supervisor->User->email = $request->email;
            if (isset($request->npassowrd)) {
                $supervisor->User->password = Hash::make($request->npassowrd);
            }

            // save supervisor
            $supervisor->salary = $request->salary;
            $supervisor->national_id = $request->nationalID;
            $supervisor->work_hours = $request->work_hours;
            $supervisor->hire_date = $request->hire_date;

            if(isset($request->status)) {$supervisor->status = 1;  $supervisor->User->status = 1;}
            else { $supervisor->status = 0; $supervisor->User->status = 0;}

            $supervisor->save();
            $supervisor->User->save();

            Session::flash('success','your Supervisor Updated successfully');
            return redirect()->back();
        } else {
            Session::flash('warning','this Supervisor is not exists any more !!');
            return redirect()->route('supervisors');
        }
    }

    function saveImage($request, $type) {

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $name = time(). $request->name .'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs/'.$type);
            $image->move($destinationPath, $name);
            return $name;
        }
        else {
            return false;
        }

    }

    function AddSupervisor () {
        return view('supervisor.add');
    }

    function storeSupervisor (Request $request) {
            $request->validate([
                "name" => "required|string" ,
                "email" => 'unique:users,email,' ,
                "passowrd" => "required|string|min:6|max:16" ,
                "salary" => "numeric" ,
                "nationalID" => "numeric" ,
                "work_hours" => "integer" ,
                "hire_date" => "date" ,
                "image" => "max:2048"
            ]);
            // save new object from user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->passowrd);
            $user->token = str_random(32);
            $check = $user->save();
            if($check) {
                // check image
                $imageName = $this->saveImage($request , 'users');
                if($imageName != false) {
                    // create new profile
                    $profile =  new Profile();
                    $profile->User_id = $user->id;
                    $profile->image = $imageName;
                    $profile->save();
                }
                // save supervisor
                $supervisor = new Supervisor();
                $supervisor->user_id = $user->id;
                $supervisor->salary = $request->salary;
                $supervisor->national_id = $request->nationalID;
                $supervisor->work_hours = $request->work_hours;
                $supervisor->hire_date = $request->hire_date;

                if(isset($request->status)) {$supervisor->status = 1;  $user->status = 1;}
                else { $supervisor->status = 0; $user->status = 0;}

                $supervisor->save();

                Session::flash('success','your Supervisor Added successfully');
                return redirect()->route('supervisors');
            }
            else {
                Session::flash('warning','Failed to save new supervisor !!');
                return redirect()->back();
            }

    }
}
