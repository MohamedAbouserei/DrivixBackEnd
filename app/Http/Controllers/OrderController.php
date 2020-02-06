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
use App\Feedback;
use Carbon\Carbon;
use DB;


class OrderController extends Controller
{
    
    public function WinchDriverOrders(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'winchdriver_id' =>'required|integer|exists:order,winchdriver_id'
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }
            $Orders = Order::where('winchdriver_id' , $request->winchdriver_id )->limit(10)->get();
            if($Orders){
                foreach ($Orders as $order) {
                    $order->Feedback;
                }
            }
            return response()->json($Orders,200);
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'], 400);
        }
    }

    public function CustomerOrders(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'User_id' =>'required|integer|exists:order,User_id'
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }
            $Orders = Order::where('User_id' , $request->User_id )->limit(10)->get();
            if($Orders){
                foreach ($Orders as $order) {
                    $order->Feedback;
                }
            }
            return response()->json($Orders,200);
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'], 400);
        }
    }
    
    public function NearestTenWinchDrivers(Request $request){
        if (!isset($request->lat) || !isset($request->long)) {
            return response()->json(['msg' => 'latitude and longitude is required'], 500);
        } else {
            $nearestLocation = DB::table("rolelocation")
                ->select("rolelocation.role_id" , "rolelocation.location" , "rolelocation.lat" , "rolelocation.long" ,"role.name" , "role.description"
                    , DB::raw("6371 * acos(cos(radians(" . $request->lat . ")) 
                            * cos(radians(rolelocation.lat)) 
                            * cos(radians(rolelocation.long) - radians(" . $request->long . ")) 
                            + sin(radians(" . $request->lat . ")) 
                            * sin(radians(rolelocation.lat))) AS distance"))
                ->join('role',function ($join) {
                    $join->on('rolelocation.role_id', '=', 'role.id')
                        ->where('role.type', '=', '0')
                        ->where ('role.status' , '1');
                })->join('winchdriver',function($j){
                    $j->on('rolelocation.role_id', '=', 'winchdriver.role_id')
                        ->where('winchdriver.availability', '=', '1');
                })
                ->limit(10)
                ->orderBy('distance', 'asc')
                ->get();

            // get reviews
            foreach($nearestLocation as $role) {
                $role_id = $role->role_id ;
                $number_of_drivixs_rate = 0;
                $total_drivix_rate = (float) 0.0;
                
                $targetRole = Role::find($role_id);
                $img = $targetRole->Roleimgs->where('type',1)->first();
                if($img){
                    $role->driverPic = 'http://www.drivixcorp.com/api/storage/'.$img->image.'/RolesImgs';
                }
                $driver = Winchdriver::where('role_id',$role_id)->first();
                $orders = $driver->Order;
                
                foreach($orders as $order) {
                    if($order->Feedback){
                        $number_of_drivixs_rate ++ ;
                        $total_drivix_rate += (float) $order->Feedback->rate;                        
                    }
                }
                $role->driverID = $driver->id;
                
                $role->num_Drivix_review = $number_of_drivixs_rate;
                if($number_of_drivixs_rate != 0)
                    $role->Drivix_rate = number_format((float)($total_drivix_rate/ $number_of_drivixs_rate), 2, '.', '');
                else
                    $role->Drivix_rate= 0.00;
            }
            return response()->json($nearestLocation, 200);
        }
    }
    
    public function makeWinchOrder(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'User_id' => 'required|integer|exists:users,id',
                'winchdriver_id' => 'required|integer|exists:winchdriver,id',
                'user_location' => 'required|string|max:300|min:5',
                'user_lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                'user_long' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
                'winch_location' => 'required|string|max:300|min:5',
                'winch_lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                'winch_long' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }
            $Order = new Order;
            $Order->User_id = $request->User_id;
            $Order->winchdriver_id = $request->winchdriver_id;
            $Order->user_location = $request->user_location;
            $Order->user_lat = $request->user_lat;
            $Order->user_long = $request->user_long;
            $Order->winch_location = $request->winch_location;
            $Order->winch_lat = $request->winch_lat;
            $Order->winch_long = $request->winch_long;
            $Order->time = Carbon::now();
            $Order->save();
            return response()->json(['msg'=> 1],200);
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'.$ex->getMessage()], 400);
        }
    }

    public function startTrip(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'winchdriver_id' => 'required|integer|exists:winchdriver,id',
                'orderID' => 'required|integer|exists:order,id',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }
            $Order = Order::where('id',$request->orderID)->where('winchdriver_id',$request->winchdriver_id)->first();
            if($Order){
                $Order->status = 3;
                $Order->save();
                return response()->json(['msg'=> 1],200);
            }
            return response()->json(['msg'=> 0],200);
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'.$ex->getMessage()], 400);
        }
    }

    public function userCancelTrip(Request $request){
        try{
            $validate=Validator::make($request->all(),[  
                'token' => 'required|string|exists:users,token',
                'orderID' => 'required|integer|exists:order,id',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }
            $user = User::where('token', $request->token)->first();
            $Order = Order::where('id',$request->orderID)->where('User_id',$user->id)->first();
            if($Order){
                $Order->status = 5;
                $Order->save();
                return response()->json(['msg'=> 1],200);
            }
            return response()->json(['msg'=> 0],200);
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'.$ex->getMessage()], 400);
        }
    }

    public function finishTrip(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'winchdriver_id' => 'required|integer|exists:winchdriver,id',
                'orderID' => 'required|integer|exists:order,id',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 500);
            }
            $Order = Order::where('id',$request->orderID)->where('winchdriver_id',$request->winchdriver_id)->first();
            if($Order){
                $Order->status = 4;
                $Order->save();
                return response()->json(['msg'=> 1],200);
            }
            return response()->json(['msg'=> 0],200);
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'.$ex->getMessage()], 400);
        }
    }

    public function addTripFeedBack(Request $request){
        try{
            $validate=Validator::make($request->all(),[       
                'token' => 'required|string|exists:users,token',
                'orderID' => 'required|integer|exists:order,id',
                'rate' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|min:3|max:300',
            ]);
            if ($validate->fails()){
                $errors = $validate->errors();
                return Response()->json($errors, 400);
            }
            $user = User::where('token', $request->token)->first();
            $Feedback = Feedback::where('order_id',$request->orderID)->first();
            if(!$Feedback){
                $Feedback = new Feedback;
            }
            $Feedback->order_id = $request->orderID;
            $Feedback->rate = $request->rate;
            $Feedback->comment = $request->comment;
            $Feedback->save();
            return response()->json(['msg'=> 1],200);
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'.$ex], 500);
        }
    }
}
