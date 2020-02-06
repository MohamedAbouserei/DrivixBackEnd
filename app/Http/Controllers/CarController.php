<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Validator;
use App\Car;
use App\User;

use Illuminate\Http\Request;


class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
    {
        try {
            if (!$request->token) {
                return response()->json(['msg' => 'You must login first'], 505);
            }
            $user = User::where('token', '=', $request->token)->first();
            if($user){
                $car = Car::where('User_id', '=', $user->id)->get();
                return Response()->json($car, 200);
            }
            return response()->json([],200);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'failed!' . $ex->getMessage()], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validate=Validator::make($request->all(),[       
                'token' => 'required|string|exists:users,token',
                'year'  => 'required|integer|min:1990',
                'color' => 'required|string|max:20|min:1',
                'model' => 'required|string|max:100|min:3',
                'brand' => 'required|string|max:100|min:3',
                
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 400);
            }
            $user = User::where('token',$request->token)->first();
            $car = Car::create($request->all() + ['User_id' => $user->id]);
            return response()->json($car, 200);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'failed!' . $ex->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $validate=Validator::make($request->all(),[       
                'token' => 'required|string|exists:users,token',
                'year'  => 'required|integer|min:1990',
                'color' => 'required|string|max:20|min:1',
                'model' => 'required|string|max:100|min:3',
                'brand' => 'required|string|max:100|min:3',
                'carID' => 'required|exists:car,id',
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 400);
            }
            $user = User::where('token',$request->token)->first();
            $car = Car::where('id',$request->carID)->where('User_id',$user->id)->first();
            if($car){
                $car->update($request->all());
                return response()->json($car, 200);
            }
            return response()->json(['msg' => 'un authorize user'], 300);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'failed!' . $ex->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $validate=Validator::make($request->all(),[       
                'token' => 'required|string|exists:users,token',
                'carID' => 'required|exists:car,id',
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 400);
            }
            $user = User::where('token',$request->token)->first();
            $car = $user->Car->where('id',$request->carID)->first();
            if($car){
                $car->delete();
                return response()->json(['msg' => 'Done!'], 200);
            }
            return response()->json(['msg' => 'un authorize user'], 300);
        } catch (Exception $ex) {
            return response()->json(['msg' => 'failed!' . $ex->getMessage()], 500);
        }
    }
    
}
