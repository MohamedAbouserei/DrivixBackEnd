<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
class UsersController extends Controller
{
  ///////////////////////// add user
    public function adduser(Request $request)
     {
         $user=new User();
          if($request->isMethod('POST'))
      {
        $this->validate($request,[

           'name'=>'required|max:50|string|min:5',
           'email' =>'required|email',
           'password'=>'required|max:15|alpha_num|min:5',
           'status'=>'required|max:50|string|min:5',
           'type'=>'required|max:25|string|min:5',

       ]);
        $user = User::where('email', '=', $request->input('email'))->first();
   if ($user === null) { // adding user
  $user->name=$request->input('name');
  $user->email=$request->input('email');
  $user->password=bcrypt($request->input('password'));
  $user->type=$request->input('type');
  $user->status=$request->input('status');
  $user->save();
  return redirect('/adduser')->with('success','Database updated');
         }
          else{return "email found";}
       }
     elseif($request->isMethod('GET')){
     return view('adduser');
   }
   }

   //////////////////////////////////////////list users
   public function showuser()
    {
        $users=User::all();
        return view('users',compact('users'));
    }

    /////////////////////////////////////////////delete user
    public function deleteuser($id)
  { if(User::findOrFail($id)){
        $user=User::findOrFail($id);
        $user->delete();
       return redirect('users')->with(['message'=>'User Deleted Successfully']);
  }
  return redirect('users')->with(['message'=>'No Such person']);
  }
  ///////////////////////////////////////update users
  public function updateuser(Request $request,$id)
  {$user=User::findOrFail($id);
    if($request->isMethod('POST'))
  {
     if(User::findOrFail($id))
  {

      $this->validate($request,[

         'name'=>'required|max:50|string|min:5',
     ]);
       $user->name=$request->input('name');
       $user->email=$request->input('email');
       $user->password=bcrypt($request->input('password'));
       $user->type=$request->input('type');
       $user->status=$request->input('status');
       $user->save();
    return redirect('/users')->with('success','Database updated');
  }
  else
  {
    return redirect('/users')->with('fail','no such person');
  }
  }
  elseif($request->isMethod('GET'))
  return view('updateuser',compact('user'));
  }

}
