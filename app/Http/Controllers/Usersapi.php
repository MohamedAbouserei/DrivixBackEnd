<?php

namespace App\Http\Controllers;
use App\User;
Use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Usersapi extends Controller
{/////////////////show users only via third party
  public function showuser()
   {
     $users= User::all();
       return response()->json($users);
   }
/////////////////add users only via third party
      public function adduser(Request $request)
   {
    try{
     $this->validate($request,[

        'name'=>'required|max:50|string|min:5',
        'email' =>'required|email',
        'password'=>'required|max:15|alpha_num|min:5',
    ]);
    }
 catch(Exception $ex)
  {
return response()->json(['msg' => 'failed!'.$ex->getMessage()], 400);
  }     

    
$user = User::where('email', '=', $request->input('email'))->first();
if ($user === null) {
  $user = new User();
  $user->name = $request->name;
  $user->email = $request->email;
  $user->password = Hash::make($request->password);
  $user->save();
 
    return response()->json(['msg' => 'Success! You have been registered!'], 200);

}
elseif($user)
{
  
    return response()->json(['msg' => 'email found'], 401);

}


 }
/////////////////delete a user only via third party
   public function deleteuser($id)
   {
    try{
        $user=User::findOrFail($id);
        $user->delete();

        return response()->json(['msg' => 'Success! deleted!'], 200);
        }
        catch(Exception $ex)
  {
return response()->json(['msg' => 'failed!'.$ex->getMessage()], 400);
  }
  }
   ////////////////////////////////////update a user threw a thrid party
   public function updateuser(Request $request,$id)
  {
    try{
    $user=User::findOrFail($id);
    if($request->isMethod('POST'))
  {
     if(User::findOrFail($id))
  {
       
      $this->validate($request,[

        'name'=>'max:50|string|min:5',
        'email' =>'email',
        'password'=>'max:15|alpha_num|min:5',
        'status'=>'max:25|string|min:5',
        'type'=>'max:25|string|min:5',

    ]);
     
       $user->name=$request->input('name');
       $user->email=$request->input('email');
       $user->password=bcrypt($request->input('password'));
       $user->type=$request->input('type');
       $user->status=$request->input('status');
       $user->save();
        return response()->json(['msg' => 'Success! updated!'], 200);
}
  else
  {
        return response()->json(['msg' => 'failed not found!'], 401);
  }
  }
  elseif($request->isMethod('GET'))
  return view('updateuser',compact('user'));
  }

catch(Exception $ex)
  {
return response()->json(['msg' => 'failed!'.$ex->getMessage()], 400);
  }

}
}