<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Exception;
use Validator;

use App\User;
use App\Role;
use App\Order;
use App\Winchdriver;
use App\Serviceprovider;
use App\Winchcompany;


class winchDriverController extends Controller
{
  
    public function changeAvaliability(Request $request){
        $validator = Validator::make($request->all() , [
            'token' => 'required|string|exists:users,token',
            'roleID' => 'required|exists:role,id',
        ]);
        
        // validate Data
        if($validator->fails())
        {
            $errors = $validator->errors();
            return Response()->json($errors, 400);
        }
        
        $user = User::where('token' , $request->token)->first();
        $checkAuth = false;
        $targetDriver= null;
        foreach($user->Serviceprovider->Role as $role){
            if($role->id == $request->roleID && $role->type == 0){
                $checkAuth = true;
                $targetDriver = $role->Winchdriver;
                break;
            }
        }
        
        if($checkAuth){
            $availabilityValue = ($targetDriver->availability)? 0 : 1;
            $targetDriver->update([
                'availability' => $availabilityValue
            ]);         
            return Response()->json(['msg' => 'availability toggle successfully'], 200);
        }
        
        return Response()->json(['msg' => 'un-authorize user'], 300);
    }

    public function acceptorder(Request $request){

        try{
            $validate=Validator::make($request->all(),[       
                'token' =>'required|exists:users,token',
                'orderID'=>'required|integer|exists:order,id',                
            ]);

            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            } 

            $user=User::where('token',$request->token)->first();
            $role=$user->Serviceprovider->Role->where('type','0')->first();
            if($role){
                $order=Order::find($request->orderID);
    
                foreach($role as $anyrole){
                    $driver = $role->Winchdriver;
                    if($driver->id == $order->winchdriver_id){
                        $order->status=1;
                        $order->save();
                        return response()->json(['msg'=>1],200);
                    }
                }
                return response()->json(['msg'=>'You are not authorized '],300);
            }
            return response()->json(['msg'=>'You are not authorized '],300);
                
        } catch(Exception $ex){
            return response()->json(['msg'=>'Please Try again.'.$ex],400);
        }
    }
    
    public function rejectorder(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'token' =>'required|exists:users,token',
                'orderID'=>'required|integer|exists:order,id',                
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }             
            $user=User::where('token',$request->token)->first();
            $role=$user->Serviceprovider->Role->where('type','0')->first();
            
            if($role){
                $order=Order::find($request->orderID);
    
                foreach($role as $anyrole){
                    $driver = $role->Winchdriver;
                    if($driver->id == $order->winchdriver_id){
                        $order->status=2;
                        $order->save();
                        return response()->json(['msg'=>0],200);
                    }
                }
                return response()->json(['msg'=>'You are not authorized '],300);
            }
            return response()->json(['msg'=>'You are not authorized '],300);
                
        } catch(Exception $ex){
            return response()->json(['msg'=>'Please Try again.'],400);
        }
    }
    
}