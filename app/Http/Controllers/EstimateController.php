<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Exception;
use Validator;
use App\User;
use App\Role;
use App\Estimate;
use Illuminate\Http\Request;

class EstimateController extends Controller{

    public function addServiceEstimate(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'token' => 'required|string|exists:users,token',
                'role_id' => 'required|integer|exists:role,id',
                'stars' => 'required|integer|min:1|max:5',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 400);
            }
            $user = User::where('token', $request->token)->first();
            $role = Role::where('id',$request->role_id)->where('type','!=','1')->first();
            if(!$role){return response()->json(['msg'=>'un authorize'],300);}
            $carService = $role->Carservice;
            if($carService){
                $newEstimate = Estimate::where('User_id',$user->id)->where('carservice_id',$carService->id)->first();
                if(!$newEstimate){
                    $newEstimate = new Estimate;
                }
                $newEstimate->stars = $request->stars;
                $newEstimate->carservice_id = $carService->id;
                $newEstimate->User_id = $user->id;
                $newEstimate->save();
                
                return response()->json(['msg'=>$request->stars],200);                
            }
            
            return response()->json(['msg'=>'Please try again'],350);                
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again' + $ex], 500);
        }
    }

}
