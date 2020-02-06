<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exception;
use Validator;

use App\User;
use App\Role;
use App\Winchdriver;
use App\Serviceprovider;
use App\Winchcompany;

use function GuzzleHttp\json_decode;


class WinchCompanyController extends Controller{
  
    public function addWinchDriver(Request $request){

        $validator = Validator::make($request->all(), [
        // check
            'token' => 'required|string|exists:users,token',
            'role_id' => 'required|numeric|exists:role,id',
            
            'price_per_km' => 'required|numeric|between:0.1,9999.99',
            'workingdays' =>'required|string|between:5,200',
            'name' =>'required|string|between:5,200',
            'description' =>'required|string|between:5,1000',
            'work_to' =>'required|string|between:5,200',
            'work_from' =>'required|string|between:5,200',
            
            'email'=>'required|email|unique:users,email',
            'password' => 'required|max:15|min:5',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }
        try {
            //check authority
            $user = User::where('token', $request->token)->first();
            $targetRole = $user->Serviceprovider->Role->where('id', $request->role_id)->where('type',1)->first();

            if ($targetRole) {
                $winchCompany=$targetRole->Winchcompany;

                // add Data
                $adduser=app('App\Http\Controllers\AuthController')->register($request);
                //create user
                $adduser2=json_decode($adduser->getContent(), true);
                if ($adduser2['email']) {
                    $token=User::where('email', $adduser2['email'])->first();
                    
                    $token->type='2';
                    $token->status = '1';
                    $token->token= md5($request->email);
                    $token->save();
                    /////create serviceprovider
                    $serviceProvider = new Serviceprovider;
                    $serviceProvider->User_id = $adduser2['id'];
                    $serviceProvider->save();
                    ///////////////////// create role
                    $role = new Role;
                    $role->serviceprovider_id= $serviceProvider->id;
                    $role->type= '0';
                    $role->status= '0';
                    $role->work_from = $request->work_from;
                    $role->work_to = $request->work_to;
                    $role->description = $request->description;
                    $role->name = $request->name;
                    $role->lock = '0';
                    $role->workingdays = $request->workingdays;
                    $role->save();
                    ////////////create winch  company driver
                    $winchdriver=new Winchdriver;
                    $winchdriver->role_id =$role->id;

                    $winchdriver->winchcompany_id =$winchCompany->id;
                    $winchdriver->price_per_km= $request->price_per_km;
                    $winchdriver->availability='1';
                    $winchdriver->save();
                    return response()->json($winchdriver, 201);
                } else{
                    return response()->json(['msg' => 'Please try again!'], 350);
                }
            }else {
                return response()->json(['msg' => 'Unauthorized!'], 300);
            }
        } catch (Exception $ex) {
            return response()->json(['msg' => 'failed wrong input data(role)!'], 500);
        }
    }

    public function getOrders(Request $request){
        $validator = Validator::make($request->all(), [
            // check
            'token' => 'required|string|exists:users,token',
            'role_id' => 'required|numeric|exists:role,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }
        try {
            $user = User::where('token', $request->token)->first();
            $targetRole = $user->Serviceprovider->Role->where('id',$request->role_id)->where('type',1)->first();
            $orders= array();
            $final=array();
            
            if ($targetRole) {
                foreach ($targetRole->winchcompany->winchdriver as $key=>$company) {
                    $orders[$key]= $company;
                }
                foreach ($orders as $key=>$orderss) {
                    $final[$key]=$orderss->order;
                }
                return response()->json($final, 200);
            } else {
                return response()->json(['msg' => 'Unauthorized!'], 300);
            }
        } catch (Exception $ex) {
            return response()->json(['msg' => 'failed wrong input data(role)!'], 500);
        }
    }
    
    public function updateWinchDriverData(Request $request){
        $validator = Validator::make($request->all(), [
        // check
            'token' => 'required|string|exists:users,token',
            'role_id' => 'required|numeric|exists:role,id',

            'driver_id' => 'required|numeric|exists:winchdriver,id',

            'price_per_km' => 'required|numeric|between:0.1,9999.99',
            'availability'=> 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }
        try {
            $user = User::where('token', $request->token)->first();
            $targetRole = $user->Serviceprovider->Role->where('id', $request->role_id)->where('type',1)->first();

            if ($targetRole) {
                $winchCompany=$targetRole->Winchcompany;

                if ($winchCompany) {
                    $winchdriver = $winchCompany->Winchdriver->where('id',$request->driver_id)->first();

                    if($winchdriver){
                        $winchdriver->price_per_km = $request->price_per_km;
                        $winchdriver->availability = $request->availability;
    
                        $winchdriver->save();
    
                        return response()->json($winchdriver, 200);
                    }
                }
            }
            return response()->json(['msg' => 'Unauthorized!'], 300);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'failed wrong input data(role)!'], 500);
        }
    }

    public function assignWinchDriver(Request $request){
        $validator = Validator::make($request->all(), [
            // check
            'token' => 'required|string|exists:users,token',
            'driver_id' => 'required|numeric|exists:winchdriver,id',
            'role_id' => 'required|numeric|exists:role,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }
        try {
            $user = User::where('token', $request->token)->first();
            $targetRole = $user->Serviceprovider->Role->where('id', $request->role_id)->where('type',1)->first();

            if ($targetRole) {
                $winchCompany=$targetRole->Winchcompany;

                if ($winchCompany) {
                    $winchdriver = Winchdriver::find($request->driver_id);

                    if($winchdriver && $winchdriver->winchcompany_id == null){
                        
                        $winchdriver->winchcompany_id=$targetRole->winchcompany->id;
                        $winchdriver->save();
                        return response()->json($winchdriver, 200);
                    }
                }
            } 
            return response()->json(['msg' => 'Unauthorized!'], 300);
    
        } catch (Exception $ex) {
            return response()->json(['msg' => 'failed wrong input data(role)!'], 500);
        }
    }
    
    public function cancelAssignWinchDriver(Request $request){
        $validator = Validator::make($request->all(), [
            // check
            'token' => 'required|string|exists:users,token',
            'driver_id' => 'required|numeric|exists:winchdriver,id',
            'role_id' => 'required|numeric|exists:role,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }
        try {
            $user = User::where('token', $request->token)->first();
            $targetRole = $user->Serviceprovider->Role->where('id', $request->role_id)->where('type',1)->first();

            if ($targetRole) {
                $winchCompany=$targetRole->Winchcompany;

                if ($winchCompany) {
                    $winchdriver = $winchCompany->Winchdriver->find($request->driver_id);

                    if($winchdriver){
                        
                        $winchdriver->winchcompany_id = null;
                        $winchdriver->save();
                        return response()->json([], 200);
                    }
                    
                }
            } 
            return response()->json(['msg' => 'Unauthorized!'], 300);
    
        } catch (Exception $ex) {
            return response()->json(['msg' => 'failed wrong input data(role)!'], 500);
        }
    }

    public function getFreeDrivers(Request $request){
        $validator = Validator::make($request->all(), [
            // check
            'token' => 'required|string|exists:users,token',
            'role_id' => 'required|numeric|exists:role,id',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }
        try {
            $user = User::where('token', $request->token)->first();
            $targetRole = $user->Serviceprovider->Role->where('id',$request->role_id)->where('type',1)->first();
            
            if ($targetRole) {
                $drivers = Winchdriver::where('winchcompany_id',null)->get();
                foreach($drivers as $driver){
                    $username = $driver->Role->Serviceprovider->User->name;
                    unset($driver->Role);
                    $driver->username  = $username;
                }
                return response()->json($drivers, 200);
            } 
            return response()->json(['msg' => 'Unauthorized!'], 300);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'failed wrong input data(role)!'], 500);
        }
    }

}