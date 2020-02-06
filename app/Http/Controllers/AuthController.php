<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
Use Exception;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function register(Request $request)
    {

        // validate function
        $validate = Validator::make($request->all(), [
            'name' => 'required|max:50|string|min:5',
            'email' => 'required|email',
            'password' => 'required|max:15|min:5',
        ]);
        // check errors
        if ($validate->fails()) {
            $errors = $validate->errors();
            return Response()->json($errors, 503);
        } else {
            // check login
            $user = User::where('email', '=', $request->input('email'))->first();
            if (!($user === null)) {
                return response()->json(['error' => 'mail already exists'], 500);
            }
            // create a new user
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'name' => $request->name,
                'type' => '0' ,
                'status' => '1',
                'token'=> md5($request->email)
            ]);
            return response()->json($user,200);

        }
    }
     public function login(Request $request)
    {

        // validate function
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|max:15||min:5',
        ]);
        // check errors
        if ($validate->fails()) {
            $errors = $validate->errors();
            return Response()->json($errors, 503);
        }
        else
        {
            $user = User::where('email',$request->email)->first();
            if($user)
            {
                $check = Hash::check($request->password ,$user->password);
                if($check)
                {
                    return response()->json($user,200);
                }
                else
                {
                    return response()->json(['email or password is wrong '], 502);
                }
            }
            else
            {
                return response()->json(['message' , 'unAuthorized email and password'],500);
            }
        }

    }
}
