<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Validator;
use App\Carserviceoffers;
use App\Role;
use App\User;
use App\Carservice;
use App\Serviceprovider;
use Storage;
use File;

class OffersController extends Controller{
    
    // get all offers to users
    public function getLastestOffers(){
        try{
            $offers=Carserviceoffers::orderBy('created_at','DESC')->limit(20)->get();
            foreach($offers as $offer){
                $offer->img='http://www.drivixcorp.com/api/storage/'.$offer->img.'/offers';                                                        
            }
            return response()->json($offers,200);
        }catch(Exception $ex)
        {
            return response()->json(['msg'=>'Please Try again'],400);
        }
    }
    
    // getSpecificOffer
    public function getSpecificOffer(Request $request){
        try{
            $validater=Validator::make($request->all(),[
                'offerID'=>'required|integer|exists:carserviceoffers,id'
            ]);
            if ($validater->fails())
            {
                $errors=$validater->errors();
                return response()->json($errors,500);
            }
            $offer=Carserviceoffers::find($request->offerID);
            $offer->img='http://www.drivixcorp.com/api/storage/'.$offer->img.'/offers';
            return response()->json($offer,200);
        }catch (Exception $ex){
            return response()->json(['msg'=>'Please Try again'],400);
        }
    }
    
    // roleOffers
    public function roleOffers(Request $request){
        try{
            $validater=Validator::make($request->all(),[
                'role_id'=>'required|integer|exists:role,id',
            ]);
            if ($validater->fails()) {
                $errors=$validater->errors();
                return response()->json($errors,500);
            }
            $role=Role::where('id',$request->role_id)->where(function($q) {
                $q->where('type', 2)->orWhere('type', 3);
            })->first();
            if($role){
                $offers = $role->Carservice->Carserviceoffers;
                foreach($offers as $offer){
                    $offer->img='http://www.drivixcorp.com/api/storage/'.$offer->img.'/offers';                                                        
                }
                return response()->json($offers,200);
            }
            return response()->json(['msg' => 'This car Service not exists'],300);
        } catch(Exception $ex) {
            return response()->json(['msg'=>'Please Try again'],400);
        }
    }
    
    
    public function is_base64($base64Image) {
    	return (bool) preg_match('`^[a-zA-Z0-9+/]+={0,2}$`', $base64Image);
    }
    
    public function addimage($image,$type){
        $decodedImage = base64_decode($image);
    	$imageName = rand(1, 999) .'_offer_'. time() . '.png';
    	$fp = fopen(public_path() . '/imgs/' . $type . '/' . $imageName, 'wb+');
    	fwrite($fp, $decodedImage);
    	fclose($fp);
    	return $imageName;
    }

    public function addOffer(Request $request){
       try{
            $validater=Validator::make($request->all(),[
                'token'=>'required|exists:users,token',
                'startDate'=>'required|date',
                'endDate'=>'required|date|after:startDate',
                'description'=>'required|string|min:5|max:300',
                'title'=>'required|string|min:5|max:50',
                'role_id' => 'required|integer|exists:role,id'
            ]);
            if ($validater->fails()){
                $errors=$validater->errors();
                return response()->json($errors,500);
            }
            $user=User::where('token',$request->token)->first();

            $role=Role::where('id',$request->role_id)->where('serviceprovider_id',$user->Serviceprovider->id)->where(function($q) {
                $q->where('type', 2)->orWhere('type', 3);
            })->first();

            if($role){
                $offer = new Carserviceoffers();
                $offer->startdate=$request->startDate;
                $offer->enddate=$request->endDate;
                $offer->describtion=$request->description;
                $offer->title=$request->title;
                $offer->carservice_id=$role->Carservice->id;
                if($request->img) {
                    if ($this->is_base64($request->img)){
                        $offer->img = $this->addimage($request->img,'OffersImgs');   
                        $offer->save();
                        return response()->json(['msg' => 'Offer added successfully'],200);
                    } else{
                        return response()->json(['msg'=>'Image is invalid'],500);
                    }
                }
                else{
                    return response()->json(['msg'=>'Image is required'],500);
                }
            }
            else{
                return response()->json(['msg'=>'Not authorize'],500);
            }
        }catch(Exception $ex){
            return response()->json(['msg'=>'Please Try again'],400);
        }    
    }
    
    public function editOffer(Request $request){
       try{
            $validater=Validator::make($request->all(),[
                'token'=>'required|exists:users,token',
                'startDate'=>'required|date',
                'endDate'=>'required|date|after:startDate',
                'description'=>'required|string|min:5|max:300',
                'title'=>'required|string|min:5|max:50',
                'role_id' => 'required|integer|exists:role,id',
                'offerID'=>'required|integer|exists:carserviceoffers,id',
            ]);
            if ($validater->fails()){
                $errors=$validater->errors();
                return response()->json($errors,500);
            }
            $user=User::where('token',$request->token)->first();

            $role=Role::where('id',$request->role_id)->where('serviceprovider_id',$user->Serviceprovider->id)->where(function($q) {
                $q->where('type', 2)->orWhere('type', 3);
            })->first();

            if($role){
                $offer = $role->Carservice->Carserviceoffers->find($request->offerID);
                if($offer){
                    $prevImg = $offer->img;
                    $offer->startdate=$request->startDate;
                    $offer->enddate=$request->endDate;
                    $offer->describtion=$request->description;
                    $offer->title=$request->title;
                    $offer->carservice_id=$role->Carservice->id;
                    if($request->img) {
                        if ($this->is_base64($request->img)){
                            $offer->img = $this->addimage($request->img,'OffersImgs');   
                            $check = $offer->save();
                            if($check){
                                $this->deleteImageFile($prevImg,'OffersImgs');
                            }
                            return response()->json(['msg' => 'Offer Updated successfully'],200);
                        } else{
                            return response()->json(['msg'=>'Image is invalid'],500);
                        }
                    }
                    else{
                        return response()->json(['msg'=>'Image is required'],500);
                    }
                }
            }
            return response()->json(['msg'=>'Not authorize'],500);
        
        }catch(Exception $ex){
            return response()->json(['msg'=>'Please Try again'],400);
        }    
    }

    public function deleteImageFile($image, $type) {
    	$imgPath = public_path('/imgs/' . $type . '/' . $image);
    	if (File::exists($imgPath)) {
    		File::delete($imgPath);
    	}
    }
    
    public function deleteoffer(Request $request){
        try{
            $validater=Validator::make($request->all(),[
                'token'=>'required|exists:users,token',
                'role_id' => 'required|integer|exists:role,id',
                'offerID'=>'required|integer|exists:carserviceoffers,id',
            ]);
            if ($validater->fails()){
                $errors=$validater->errors();
                return response()->json($errors,500);
            }
            
            $user=User::where('token',$request->token)->first();

            $role=Role::where('id',$request->role_id)->where('serviceprovider_id',$user->Serviceprovider->id)->where(function($q) {
                $q->where('type', 2)->orWhere('type', 3);
            })->first();

            if($role){
                $offer = $role->Carservice->Carserviceoffers->find($request->offerID);
                if($offer){
                    $this->deleteImageFile($offer->img,'OffersImgs');
                    $offer->delete();
                    return response()->json(['msg' => 'Offer deleted successfully'],200);   
                }
            }
            return response()->json(['msg' => 'Un authorize'],300);   

        } catch(Exception $ex) {
            return response()->json(['msg'=>'Please Try again..'],400);
        }
    }
    
}