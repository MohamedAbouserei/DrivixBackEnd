<?php

namespace App\Http\Controllers;
use App\Profile;
use App\User;
Use Exception;
use Storage;
use File;
use Validator;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
class Profileapi extends Controller
{
   
    public function SetImage(Request $request)
    {
        try{ 
            if(!$request->token)
            {
                return response()->json(['msg' => 'failed! You must login first'], 500);
            }
            $user=User::where('token','=',$request->token)->first();
            $profile=Profile::where('User_id',$user->id)->first();
             if(!$profile ) { return response()->json(['msg'=>'you don\'t have profile yet '],400); }
             if($profile->image != 'default.png' ) { 
                 // delete Old Image
                 $this->deleteImageFile($profile->image , 'users');
             }
           
           // check if isset Image
           if($request->input('image'))
           {
               $check_iamge = $this->is_base64($request->image);
               if($check_iamge == false) {
                    return response()->json(['msg' => 'pls enter a valid  image'], 400);
               }
               // continue working
                $imageName = $this->storeImageBase64($request->image , 'users');
                $profile->image=$imageName;
                $profile->save();
                return response()->json(['msg'=>'Image Uploaded Successfully!'],200);   
           }
           else
           {
                return response()->json(['msg' => 'Image is required'], 400);
           }
        }
        catch(Exception $ex)
        {
            return response()->json(['msg' => 'failed!'.$ex->getMessage()], 400);
        }
    }
    
        // Storing Base 64 Images
    public function storeImageBase64($base64Image, $type) {
    	$decodedImage = base64_decode($base64Image);
    	$imageName = rand(1, 999) . time() . '.png';
    	$fp = fopen(public_path() . '/imgs/' . $type . '/' . $imageName, 'wb+');
    	$fp_thumb = fopen(public_path() . '/imgs/' . $type . '/thumb/' . $imageName, 'wb+');
    	fwrite($fp, $decodedImage);
    	fclose($fp);
    	fwrite($fp_thumb, $decodedImage);
    	fclose($fp_thumb);
    	return $imageName;
    }
    
    // Check IF Image Is base64>?
    public function is_base64($base64Image) {
    	return (bool) preg_match('`^[a-zA-Z0-9+/]+={0,2}$`', $base64Image);
    }
    

    function deleteImageFile($image, $type) {
    	$imgPath = public_path('/imgs/' . $type . '/thumb' .'/'. $image);
    	$imgBeforeEditPath = public_path('/imgs/' . $type . '/' . $image);
    
    	if (File::exists($imgPath)) {
    		File::delete($imgPath);
    	}
    	if (File::exists($imgBeforeEditPath)) {
    		File::delete($imgBeforeEditPath);
    	}
    }

    public function deletepic(Request $request){
        try
        {
            if(!$request->token)
            {
                return response()->json(['msg' => 'failed! You must login first'], 500);
            }
            $user=User::where('token','=',$request->token)->first();
            $profileimg=Profile::where('User_id',$user->id)->first();
            if(!$profileimg) { return response()->json(['msg'=>'you don\'t have profile yet '],400); }
            
            if($profileimg->image =='default.png') {
                return response()->json(['msg'=>'No Image to be deleted'],400); 
            }
            $this->deleteImageFile($profileimg->image , 'users');
            $profileimg->image='default.png';
            $profileimg->save();
            return response()->json(['msg'=>'Deleted Successfully!'],200);
        }
        catch(Exception $ex)
        {
            return response()->json(['msg' => 'failed!'.$ex->getMessage()], 400);
        }
    }
    
    public function addprofile(Request $request)
    {
        try{
            if(!$request->token)
            {
                return response()->json(['msg'=>'You must login first'],505);
            }
            $user=User::where('token','=',$request->token)->first();
            $check=Profile::where('User_id',$user->id)->first();
           if($check)
           {
               return response()->json(['msg'=>'Profile aleady exist!'],504);
           }
            $validate=Validator::make($request->all(),[       
               'phone' =>'required|max:50|min:5|unique:profile,phone',
               'gender'=>'required',
               'DOB'=>'required|date|max:10|min:5',
               'location'=>'required|max:25|min:5',
               'job'=>'required|max:25|min:5',
               'token'=>'required',
           ]);
           if ($validate->fails()) {
            $errors = $validate->errors();
            return Response()->json($errors, 503);
           }
           $profile=new Profile();
           $profile->User_id= $user->id;
           $profile->phone= $request->phone;
           $profile->gender= $request->gender;
           $profile->DOB= $request->DOB;
           $profile->image='default.png';           
           $profile->location= $request->location;
           $profile->job= $request->job;
           $profile->save();
           return response()->json(['msg' => 'Profile Added Successfully'], 200);
          }
        catch(Exception $ex)
        {
            return response()->json(['msg' => 'failed!'.$ex->getMessage()], 400);
        }             
    }
    public function showprofile($token)
    {
        try
        {
            
            if(!$token)
            {
                return response()->json(['msg'=>'You must login first'],505);
            }
            $user=User::where('token','=',$token)->first();
            $profile=Profile::where('User_id',$user->id)->first();
            $profile->image = 'http://www.drivixcorp.com/api/storage/'.$profile->image.'/users';
            return response()->json($profile,200);
        }
        catch(Exception $ex){
            return response()->json(['msg'=>'User Not exist' . $ex],400);
        }
    }
    
    public function showallprofiles()
    {
        $profiles=Profile::all();
        return response()->json($profiles);
    }
    public function deleteprofile(Request $request)
    {
        try{
            if(!$request->token)
            {
                return response()->json(['msg'=>'You must login first'],505);
            }
            $user=User::where('token','=',$request->token)->first();
            $profile=Profile::where('User_id',$user->id)->first();
            //$this->deletepic($profile->image);
            if($profile)
            {
                $profile->delete();
            }
            else{
                return response()->json(['msg'=>'User not exist!'],506);
            }
            return response()->json(['msg' => 'Success! deleted!'], 200);
            }
        catch(Exception $ex){
            return response()->json(['msg' => 'failed!'.$ex->getMessage()], 400);
        }
    }
    public function updateprofile(Request $request,$token)
    {
        try{
            if(!$token)
            {
                return response()->json(['msg'=>'You must login first'],505);
            }
            $user=User::where('token','=',$token)->first();
            $profile=Profile::where('User_id',$user->id)->first();              
            $validate=Validator::make($request->all(),[
               'phone' =>'required|max:50|min:5',
               'gender'=>'required',
               'DOB'=>'required|date|max:10|min:5',
               'location'=>'required|max:25|min:5',
               'job'=>'required|max:25|min:5',
            ]); 
            if ($validate->fails()) {
            $errors = $validate->errors();
            return Response()->json($errors, 503);
            }
            $profile->phone= $request->phone;
            $profile->gender= $request->gender;
            $profile->DOB= $request->DOB;           
            $profile->location= $request->location;
            $profile->job= $request->job;
            $profile->save();
            return response()->json(['msg' => 'Success! updated!'], 200);
    }
    catch(Exception $ex)
          {
                return response()->json(['msg' => 'failed!'.$ex->getMessage()], 400);
          }        
        }        
}

