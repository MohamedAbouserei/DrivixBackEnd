<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use Exception;
use App\Parking;
use Illuminate\Http\Request;

class ParkingController extends Controller {
    
    public function getIndividualCarHistoryLocations(Request $request){
        try {
            $validate = Validator::make($request->all(), [
                'token' => 'required|string|exists:users,token',
                'car_id' => 'required|exists:car,id',

            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 503);
            }
            $user = User::where('token', $request->token)->first();
            $car = $user->Car->find($request->car_id);
            if($car){
                $locations = $car->Location;
                if($locations){ 
                    foreach($locations as $location){
                        $location->Parking;
                    }
                    return response()->json($parking, 201);
                }
                else{return response()->json(['msg' => 'no Parking Place with this Car!'], 400);}
                              
            }
            else {
                return response()->json(['msg' => 'Unauthorized!'], 400);
            }
        }

        catch(Exception $ex){
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 400);
        }
    }
    
    public function getLastestCarLocation(Request $request){
        try {
            $validate = Validator::make($request->all(), [
                'token' => 'required|string|exists:users,token',
                'car_id' => 'required|exists:car,id',

            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 503);
            }
            $user = User::where('token', $request->token)->first();
            $car = $user->Car->find($request->car_id);
            if($car){
                $location = $car->Location->sortByDesc('updated_at')->first();
                if($location){ 
                    $location->Parking;
                    return response()->json($location, 201);
                }
                else{return response()->json(['msg' => 'no Parking Place with this Car!'], 400);}
                              
            }
            else {
                return response()->json(['msg' => 'Unauthorized!'], 400);
            }
        }

        catch(Exception $ex){
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeParking(Request $request){
        try{
            $validate = Validator::make($request->all(), [
                'token' => 'required|string|exists:users,token',
                'car_id' => 'required|exists:car,id',
                'location_id' => 'required|exists:location,id',
                'type' => 'required|in:0,1',
                'cost_limit' => 'required|between:0,999.99',
                'cost_hour' => 'required|between:0,99.99',
                'hour_limit'=>'required|between:0,99.99',
                'parking_time' => 'required|numeric',
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 503);
            }
            $user = User::where('token', $request->token)->first();
            $car = $user->Car->find($request->car_id);
            if($car){
                $location = $car->Location->find($request->location_id);
                if($location){ 
                    $parking = Parking::create($request->all());
                    return response()->json($parking, 201);
                }
                return response()->json(['msg' => 'No location for this car!'], 400);
            }
            return response()->json(['msg' => 'No car found!'], 400);
          }

        catch(Exception $ex) {
            return response()->json(['msg' => 'error!'], 400);
        }
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateParking(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'token' => 'required|string|exists:users,token',
                'car_id' => 'required|exists:car,id',
                'location_id' => 'required|exists:location,id',
                'cost_limit' => 'required|between:0,999.99',
                'cost_hour' => 'required|between:0,99.99',
                'hour_limit'=>'required|between:0,99.99',
                'parking_time' => 'required|numeric',
                'type' => 'required|numeric',
                'parking_id' => 'required|exists:parking,id',

            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 503);
            }
            $user = User::where('token', $request->token)->first();
            $flag = false;
            $scar=null;
            $sloc=null;

            foreach ($user->Car as $car) {
                if ($car->id == $request->car_id) {
                    $flag = true;
                    $scar=$car;
                    break;
                }
            }

            foreach ($scar->Location as $loc) {
                if ($loc->id == $request->location_id) {
                    $sloc=$loc;
                    break;
                }
            }
            if ($user) {
                if ($flag) {
                    if ($sloc) {
                        $parking = $sloc->Parking->find($request->parking_id);
                        if ($parking) {
                            $parking->update($request->all());
                            return response()->json($parking, 200);
                        } else {
                            return response()->json(['msg' => 'no location with this id!'], 400);
                        }
                    } else {
                        return response()->json(['msg' => 'add location before u leave the car!'], 400);
                    }
                } else {
                    return response()->json(['msg' => 'U dont have a car at least add one!'], 400);
                }
            } else {
                return response()->json(['msg' => 'Unauthorized!'], 400);
            }
        } catch (Exception $ex) {
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteParking(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'token' => 'required|string|exists:users,token',
                'car_id' => 'required|exists:car,id',
                'location_id' => 'required|exists:location,id',
                'parking_id' => 'required|exists:parking,id',

            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 503);
            }
            $user = User::where('token', $request->token)->first();

            $scar=null;
            $sloc=null;

            foreach ($user->Car as $car) {
                if ($car->id == $request->car_id) {
                    $flag = true;
                    $scar=$car;
                    break;
                }
            }

            foreach ($scar->Location as $loc) {
                if ($loc->id == $request->location_id) {
                    $sloc=$loc;
                    break;
                }
            }

            if ($user) {
                if ($flag) {
                    if ($sloc) {

                        $parking = $sloc->Parking->find($request->parking_id);
                      if ($parking) {
                          $parking->delete();
                          return response()->json(['msg' => 'Done!'], 200);
                        }
            else{return response()->json(['msg' => 'no location with this id!'], 400);}
                    } else {
                            return response()->json(['msg' => 'add location before u leave the car!'], 400);
                        }
                    } else {
                            return response()->json(['msg' => 'U dont have a car at least add one!'], 400);
                        }
                              }
                              else {
                                return response()->json(['msg' => 'Unauthorized!'], 400);
                            }
        }

        catch(Exception $ex)
        {
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 400);

        }
    }
}
