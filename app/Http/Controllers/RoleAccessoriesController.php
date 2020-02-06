<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Validator;

use App\User;
use App\Role;

use App\Rolephone;
use App\Rolelocation;
use App\Roleimgs;

use App\Winchcompany;
use App\Winchdriver;
use App\Carservice;
use App\Workshop;
use App\Sparesshop;
use App\Winchcompanybranches;

use File;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class RoleAccessoriesController extends Controller
{
    // phone functionality
    public function userManageRolePhone(Request $request){
        $validator = Validator::make($request->all() , [
            'token' => 'required|string|exists:users,token',
            'roleID' => 'required|exists:role,id',
            'phone' => 'required|string|min:5|max:15',
            'phoneID' => 'nullable|exists:rolephone,id' // add/edit
        ]);
        
        // validate Data
        if($validator->fails())
        {
            $errors = $validator->errors();
            return Response()->json($errors, 400);
        }
        
        $user = User::where('token' , $request->token)->first();
        $checkAuth = false;
        $targetRole= null;
        foreach($user->Serviceprovider->Role as $role){
            if($role->id == $request->roleID){
                $checkAuth = true;
                $targetRole = $role;
                break;
            }
        }
        
        if($checkAuth){
            if($request->phoneID == null){ // add
                $targetRole->Rolephone()->create([
                    'role_id' => $request->roleID,
                    'phone' => $request->phone
                ]);
                
                return Response()->json(['msg' => 'Phone added successfully'], 200);               
            }
            else{
                $rolwPhone = Rolephone::find($request->phoneID);
                if($rolwPhone){
                    $rolwPhone->update([
                        'phone' => $request->phone
                    ]);         
                    return Response()->json(['msg' => 'Phone Edited successfully'], 200);
                }
                return Response()->json(['msg' => 'Error: try again'], 350);        
            }
        }
        
        return Response()->json(['msg' => 'un-authorize user'], 300);
    }
    
    public function deleteRolePhone(Request $request){
        $validator = Validator::make($request->all() , [
            'token' => 'required|string|exists:users,token',
            'roleID' => 'required|exists:role,id',
            'phoneID' => 'required|exists:rolephone,id'
        ]);
        
        // validate Data
        if($validator->fails())
        {
            $errors = $validator->errors();
            return Response()->json($errors, 400);
        }
        
        $user = User::where('token' , $request->token)->first();
        $checkAuth = false;
        foreach($user->Serviceprovider->Role as $role){
            if($role->id == $request->roleID){
                $checkAuth = true;
                break;
            }
        }
        
        if($checkAuth){
            $rolwPhone = Rolephone::find($request->phoneID);
            $rolwPhone->delete();
            return Response()->json(['msg' => 'Phone Delete successfully'], 200);
        }
        return Response()->json(['msg' => 'un-authorize user'], 300); 
    }
  
     
    // location functionality
    public function userManageRoleLocation(Request $request){
        $validate = Validator::make($request->all(), [
            'token' => 'required|string|exists:users,token',
            'role_id' => 'required|exists:role,id',
            'location' => 'required|string|max:300|min:5',
            'lat' => ['required', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'long' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'locationID' => 'nullable|exists:rolelocation,id',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return Response()->json($errors, 300);
        }
        $user = User::where('token', $request->token)->first();
        $flag = false;
        $targetRole = null;
        foreach ($user->Serviceprovider->Role as $role) {
            if ($role->id == $request->role_id) {
                $flag = true;
                $targetRole = $role;
                break;
            }
        }
        
        if ($flag) {
            if ($request->locationID == null) { // in case adding
                $currentLocation=$targetRole->Rolelocation->where('role_id',$targetRole->id);

                if($targetRole->type==0||$targetRole->type==1) { // check if role = winch driver or winch company
                    if($currentLocation->isempty()) {
                        $role = Rolelocation::create($request->all());
                        return response()->json($role, 200); 
                    }
                    else {
                        return response()->json('only one location is permitted for you', 500);
                    }
                }
                else { // role = workshop or spare-part
                    $role = Rolelocation::create($request->all());
                    return response()->json($role, 200);
                } 
            } else { // in case editing
                $role = $targetRole->Rolelocation->find($request->locationID);
                $role->update($request->all());
                return response()->json($role, 200);
            }
        } 
        else {
            return response()->json(['msg' => 'Unauthorized!'], 400);
        }
    }
    
    public function deleteRoleLocation(Request $request){
        $validate = Validator::make($request->all(), [
            'token' => 'required|string|exists:users,token',
            'role_id' => 'required|exists:role,id',
            'locationID' => 'required|exists:rolelocation,id',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return Response()->json($errors, 400);
        }
        $user = User::where('token', $request->token)->first();
        $flag = false;
        foreach ($user->Serviceprovider->Role as $role) {
            if ($role->id == $request->role_id) {
                $flag = true;
                break;
            }
        }
        if ($flag) {
            $locations = Rolelocation::where('role_id',$request->role_id)->get();
            if (count($locations)>1) {
                Rolelocation::destroy($request->locationID);
                return response()->json(['msg' => 'Done!'], 200);
            }
            return response()->json(['msg' => 'you must have at least one location for your service'], 500);
        }
        else {
            return response()->json(['msg' => 'Unauthorized!'], 300);
        }
    }


    // Company Branches functionality
    public function providerManageCompanyBranches(Request $request){
        try{
            $validate = Validator::make($request->all(), [
                'token' => 'required|string|exists:users,token',
                'role_id' => 'required|exists:role,id',
                'phone' => 'required|regex:/(01)[0-9]{9}/',
                'address' => 'required|string|max:100|min:1',
                'winchcompany_id'=>'required|exists:winchcompany,id',
                'branchID' => 'nullable|exists:winchcompanybranches,id',
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 400);
            }
            $user = User::where('token', $request->token)->first();
            $flag = false;
            $targetRole = null;
            foreach ($user->Serviceprovider->Role as $role) {
                if ($role->id == $request->role_id) {
                    $flag = true;
                    $targetRole = $role;
                    break;
                }
            }
            if($targetRole->type==1){
                $srole=$targetRole->Winchcompany->where('role_id',$targetRole->id);

                if ($flag) {
                    if ($request->branchID == null) { // in case adding
                        if($srole!=null) {
                            $role = Winchcompanybranches::create($request->all());
                            return response()->json($role, 200); 
                        }
                        else{
                            return response()->json(['msg' => 'no company exist'], 404);
                        }
                    }
                    else { // in case editing
                        $role = $targetRole->Winchcompany->find($request->winchcompany_id)->Winchcompanybranches->find($request->branchID);
                        if($role){
                            $role->update($request->all());
                            return response()->json($role, 200);
                        }
                        else{return response()->json(['msg' => 'no branch exist'], 500);}
                    }
                }
                else {
                    return response()->json(['msg' => 'Unauthorized!'], 600);
                }
            }
            else {
                return response()->json(['msg' => 'Unauthorized to acess this part!'], 600);
            }
        }
        catch(Exception $ex){
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 300);
        }
    }

    public function deleteCompanyBranches(Request $request){ 
        try{
            $validate = Validator::make($request->all(), [
                'token' => 'required|string|exists:users,token',
                'role_id' => 'required|exists:role,id',
                'winchcompany_id'=>'required|exists:winchcompany,id',
                'branchID' => 'required|exists:winchcompanybranches,id',
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 400);
            }
            $user = User::where('token', $request->token)->first();
            $flag = false;
            foreach ($user->Serviceprovider->Role as $role) {
                if ($role->id == $request->role_id) {
                    $flag = true;
                    break;
                }
            }
            if ($flag) {
                if (Winchcompany::find($request->winchcompany_id)->Winchcompanybranches->find($request->branchID)) {
                    Winchcompanybranches::destroy($request->branchID);
                    return response()->json(['msg' => 'Done!'], 200);
                }
                else{return response()->json(['msg' => 'Unauthorized!'], 300);}
            }
            else {
                return response()->json(['msg' => 'Unauthorized!'], 300);
            }
        }
        catch(Exception $ex){
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 500);
        }
    }


    // change Lock and status functionality
    public function changeLock(Request $request){
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
        $targetRole= null;
        foreach($user->Serviceprovider->Role as $role){
            if($role->id == $request->roleID){
                $checkAuth = true;
                $targetRole = $role;
                break;
            }
        }
        
        if($checkAuth){
            $lockValue = ($targetRole->lock)? 0 : 1;
            $targetRole->update([
                'lock' => $lockValue
            ]);         
            return Response()->json(['msg' => 'lock toggle successfully'], 200);
        }
        
        return Response()->json(['msg' => 'un-authorize user'], 300);
    }
    
    public function changeStatus(Request $request){
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
        $targetRole= null;
        foreach($user->Serviceprovider->Role as $role){
            if($role->id == $request->roleID){
                $checkAuth = true;
                $targetRole = $role;
                break;
            }
        }
        
        if($checkAuth){
            $statusValue = ($targetRole->status)? 0 : 1;
            $targetRole->update([
                'status' => $statusValue
            ]);         
            return Response()->json(['msg' => 'status toggle successfully'], 200);
        }
        
        return Response()->json(['msg' => 'un-authorize user'], 300);
    }

    // get roles functionality
    public function getProviderRoles(Request $request){
        $driverValidator = Validator::make($request->all() , [
            'token' => 'required|string|exists:users,token',
        ]);
        
        // validate Data
        if($driverValidator->fails())
        {
            $errors = $driverValidator->errors();
            return Response()->json($errors, 400);
        }
        $user = User::where('token' , $request->token)->first();
        
        $roles = $user->Serviceprovider->Role;
        foreach($roles as $role){
            $role->Roleimgs->where('type',1);
        }

        return Response()->json($roles, 200);
    }

    public function getProviderRoleDetails(Request $request){
        try{
            $validator = Validator::make($request->all() , [
                'token' => 'required|string|exists:users,token',
                'roleID' => 'required|exists:role,id',
                'type' => ['required', Rule::in([0, 1, 2, 3])],
            ]);
            
            // validate Data
            if($validator->fails()){
                $errors = $validator->errors();
                return Response()->json($errors, 400);
            }
            
            $user = User::where('token' , $request->token)->first();
            $checkAuth = false;
            $targetRole;
            foreach($user->Serviceprovider->Role as $role){
                if($role->id == $request->roleID){
                    $checkAuth = true;
                    $targetRole = $role;
                    break;
                }
            }
            
            if($checkAuth){
                if($request->type == 0){ // get Winch Driver Winchdriver
                    $driver = $targetRole->Winchdriver;
                    if($driver){
                        foreach($targetRole->Roleimgs as $img){
                            $img->image = 'http://www.drivixcorp.com/api/storage/'.$img->image.'/RolesImgs';
                        }
                        $targetRole->Rolephone;
                        $targetRole->Rolelocation;
                        
                        return Response()->json($targetRole, 200);
                    }
                    return Response()->json([], 350);
                }
                else if($request->type == 1){ // get WinchCompany
                    $company = $targetRole->Winchcompany;
                    if($company){
                        foreach($targetRole->Roleimgs as $img){
                            $img->image = 'http://www.drivixcorp.com/api/storage/'.$img->image.'/RolesImgs';
                        }
                        $targetRole->Rolephone;
                        $targetRole->Rolelocation;
                        $company->Winchcompanybranches;
                        $company->Winchdriver;
                        foreach($company->Winchdriver as $driver){
                            $driver->Role;
                        }
    
                        return Response()->json($targetRole, 200);
                    }
                    return Response()->json([], 350);
                }
                else if($request->type == 2){ // get workshop
                    $workshop = $targetRole->Carservice->Workshop;
                    if($workshop){
                        foreach($targetRole->Roleimgs as $img){
                            $img->image = 'http://www.drivixcorp.com/api/storage/'.$img->image.'/RolesImgs';
                        }
                        $targetRole->Rolephone;
                        $targetRole->Rolelocation;
                        $data = [];
                        $i = 0;
                        foreach($workshop->Workshoptype as $type){
                            $data[$i] =  DB::table('worshop_supervisor_type')->where('id', '=', $type->workshoptype)->first();
                            $i++;
                        }
                        $targetRole->workshopType = $data;
    
                        return Response()->json($targetRole, 200);
                    }
                    return Response()->json([], 350);
                }
                else if($request->type == 3){ // get sparepart
                    $spareshop = $targetRole->Carservice->Sparesshop;
                    if($spareshop){
                        foreach($targetRole->Roleimgs as $img){
                            $img->image = 'http://www.drivixcorp.com/api/storage/'.$img->image.'/RolesImgs';
                        }
                        $targetRole->Rolephone;
                        $targetRole->Rolelocation;
    
                        return Response()->json($targetRole, 200);
                    }
                    return Response()->json([], 350);
                }
            }
            
            return Response()->json(['msg' => 'un-authorize user'], 300);
        } catch(Exception $ex){
            return response()->json(['msg' => 'failed!Please Try again'], 400);
        }
    }  
 
    // change Lock and status functionality
    public function getWorkshopTypes(Request $request){
        $validator = Validator::make($request->all() , [
            'token' => 'required|string|exists:users,token'
        ]);
        
        // validate Data
        if($validator->fails())
        {
            $errors = $validator->errors();
            return Response()->json($errors, 400);
        }
        
        $workShopType = DB::select('select * from worshop_supervisor_type');         
        return Response()->json($workShopType, 200);
    }
    
    
    /* Role Images Functionality */
    // add role image
    public function addRolesImages(Request $request){
        $validator = Validator::make($request->all() , [
            'token' => 'required|string|exists:users,token',
            'role_id' => 'required|integer|exists:role,id',
            'type' => 'required|in:0,1|max:1',
        ]);
        
        // validate Data
        if($validator->fails()){
            $errors = $validator->errors();
            return Response()->json($errors, 400);
        }    
        
        try{ 
            $user = User::where('token', $request->token)->first();
            
            $targetRole = $user->Serviceprovider->Role->find($request->role_id);
            if(!$targetRole ) { return response()->json(['msg'=>'un authorize '],300); }

            // check if isset Image form body
           if($request->images){
                $check_images =  false;
                foreach($request->images as $img){
                    // do spilt check
                    $check_images = $this->is_base64($img);
                    if(!$check_images){break;}
                }
                
                if(!$check_images){
                    return response()->json(['msg' => 'please enter a valid  image'], 350);
                }
                // continue working
                foreach($request->images as $img){
                    $imageName = $this->storeImageBase64($img , 'RolesImgs');
                    $roleImg = new Roleimgs;
                    $roleImg->image = $imageName;
                    $roleImg->role_id = $request->role_id;
                    $roleImg->date = Carbon::now();
                    $roleImg->type = $request->type;
                    $roleImg->save();
                }
                return response()->json(['msg'=>'Image Uploaded Successfully!'],200);   
           }else{
                return response()->json(['msg' => 'Image is required'], 350);
            }
            
        }catch(Exception $ex){
            return response()->json(['msg' => 'failed!'.$ex->getMessage()], 500);
        }
    }
    
    // Storing Base 64 Images
    public function storeImageBase64($base64Image, $type) {
    	$decodedImage = base64_decode($base64Image);
    	$imageName = rand(1, 999) .'_role_' . time() . '.png';
    	$fp = fopen(public_path() . '/imgs/' . $type . '/' . $imageName, 'wb+');
    	fwrite($fp, $decodedImage);
    	fclose($fp);
    	return $imageName;
    }
    
    // Check IF Image Is base64
    public function is_base64($base64Image) {
    	return (bool) preg_match('`^[a-zA-Z0-9+/]+={0,2}$`', $base64Image);
    }
    
    // delete role image
    public function deleteRolesImage(Request $request){
        $validator = Validator::make($request->all() , [
            'token' => 'required|string|exists:users,token',
            'role_id' => 'required|integer|exists:role,id',
            'roleImg_ID' => 'required|integer|exists:roleimgs,id',
        ]);
        
        // validate Data
        if($validator->fails()){
            $errors = $validator->errors();
            return Response()->json($errors, 400);
        }    
        
        try{ 
            $user = User::where('token', $request->token)->first();
            
            $targetRole = $user->Serviceprovider->Role->find($request->role_id);
            if(!$targetRole) { return response()->json(['msg'=>'un authorize'],300); }

            $targetImage = $targetRole->Roleimgs->find($request->roleImg_ID);
            if(!$targetImage ) { return response()->json(['msg'=>'un authorize'],300); }
            
            $this->deleteImageFile($targetImage->image , 'RolesImgs');

            $targetImage->delete();
            return response()->json(['msg'=>'Deleted Successfully!'],200);

        }
        catch(Exception $ex){
            return response()->json(['msg' => 'failed!'.$ex->getMessage()], 500);
        }
    }
    
    public function deleteImageFile($image, $type) {
    	$imgPath = public_path('/imgs/' . $type . '/' . $image);
    	if (File::exists($imgPath)) {
    		File::delete($imgPath);
    	}
    }
    
}