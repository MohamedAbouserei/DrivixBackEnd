<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Session;
use Validator;
use App\User;
use App\Role;
use App\Serviceprovider;
use App\Winchcompany;
use App\Winchdriver;
use App\Carservice;
use App\Workshop;
use App\Sparesshop;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use DB;
use Yajra\Datatables\Datatables;

class RoleController extends Controller
{
  
    public function userManageRole(Request $request){
        $checkProvider = false;
        $checkNewRole = false;
        $serviceProvider = null;
        
        $roleValidator = Validator::make($request->all() , [
            // check user authorize
            'token' => 'required|string|exists:users,token',
            'type' => ['required', Rule::in([0, 1, 2, 3])],
            'workingdays' =>'required|string|between:5,200',
            'name' =>'required|string|between:5,200',
            'description' =>'required|string|between:5,1000',
            'work_to' =>'required|string|between:5,200',
            'work_from' =>'required|string|between:5,200',
            'roleID' => 'nullable|exists:role,id' // add/edit
        ]);
        
        // validate Data
        if($roleValidator->fails())
        {
            $errors = $roleValidator->errors();
            return Response()->json($errors, 400);
        }
        
        $user = User::where('token' , $request->token)->first();
        
        if($request->roleID == null){ // means add
            $checkNewRole = true;
            // getServiceProvider
            $serviceProvider = Serviceprovider::where('User_id',$user->id)->first();
            $role = new Role;
            $role->type = $request->type; // type cann't be change in Edit
            if($serviceProvider){
                $role->serviceprovider_id = $serviceProvider->id;
            }
            else{
                $checkProvider = true;
                // create serviceProvider if not exists
                $serviceProvider = new Serviceprovider;
                $serviceProvider->User_id = $user->id; 
                $r = $serviceProvider->save();
                if($r){
                    $role->serviceprovider_id = $serviceProvider->id;
                }
                else{
                    return Response()->json(['msg' => 'Error: Please try again'], 300);
                }
            }
        }
        else{ // means edit
            $role = Role::where('id',$request->roleID)->where('type',$request->type)->first();
            if(!$role){
                return Response()->json(['msg' => 'ERROR: There\' no Service with this name'], 350);
            }
        }
        
        // assing request data to Role
        $role->work_from = $request->work_from;
        $role->work_to = $request->work_to;
        $role->description = $request->description;
        $role->name = $request->name;
        $role->workingdays = $request->workingdays;
        
        $result = $role->save();
        
        if($result){
            if($request->type == 0){
                return $this->userManageWinchDriver($request,$role,$checkNewRole,$checkProvider,$serviceProvider);
            }
            else if($request->type == 1){
                return $this->userManageWinchCompany($request,$role,$checkNewRole,$checkProvider,$serviceProvider);
            }
            else {
                return $this->userManageCarService($request,$role,$checkNewRole,$checkProvider,$serviceProvider);
            }
        }
        else{
            if($checkProvider){
                $serviceProvider->delete();
            }
            return Response()->json(['msg' => 'ERROR: Please try again'], 300);
        }
    }
    
    public function userManageWinchCompany($request,$role,$checkNewRole,$checkProvider,$serviceProvider){
        $companyValidator = Validator::make($request->all() , [
            // check user authorize
            'companyType' => ['required', Rule::in([0, 1])], // 0 local, 1 International
        ]);
        // validate Data
        if($companyValidator->fails())
        {
            if($checkProvider){
                $serviceProvider->delete();
            }
            if($checkNewRole){
                $role->delete();
            }
            $errors = $companyValidator->errors();
            return Response()->json($errors, 400);
        }
        
        if($request->roleID == null){ // means add
            $winchCompany = new Winchcompany;
            $winchCompany->company_type = $request->companyType;  
            $winchCompany->role_id = $role->id;
            $winchCompany->save();
            return Response()->json(['msg' => 'Winch Company added Successfully'], 200);
        }
        else{ // means edit
            $winchCompany = Winchcompany::where('role_id',$role->id)->first();
            $winchCompany->company_type = $request->companyType;  
            $winchCompany->save();
            
            return Response()->json(['msg' => 'Winch Company edited Successfully'], 200);
        }
    }
    
    public function userManageWinchDriver($request,$role,$checkNewRole,$checkProvider,$serviceProvider){
        $driverValidator = Validator::make($request->all() , [
            // check user authorize
            'price_per_km' => 'required|numeric|between:0.1,9999.99',
            'winchcompany_id' => 'nullable|exists:winchcompany,id'
        ]);
        // validate Data
        if($driverValidator->fails())
        {
            if($checkProvider){
                $serviceProvider->delete();
            }
            if($checkNewRole){
                $role->delete();
            }
            $errors = $driverValidator->errors();
            return Response()->json($errors, 400);
        }
        
        if($request->roleID == null){ // means add
            $checkwinch = Role::where('serviceprovider_id',$serviceProvider->id)->where('type',0)->count();
            if($checkwinch > 1){
                
                if($checkProvider){
                    $serviceProvider->delete();
                }
                if($checkNewRole){
                    $role->delete();
                } 

                return Response()->json(['msg'=>'You have Registered yourself as winch driver before'], 450);
            }
            $winchDriver = new Winchdriver;
            $winchDriver->price_per_km = $request->price_per_km;
            $winchDriver->winchcompany_id = $request->winchcompany_id;
            $winchDriver->role_id = $role->id;
            $winchDriver->save();
            return Response()->json(['msg' => 'Winch Driver added Successfully'], 200);
        }
        else{ // means edit
            $winchDriver = Winchdriver::where('role_id',$role->id)->first();
            $winchDriver->price_per_km = $request->price_per_km;
            $winchDriver->winchcompany_id = $request->winchcompany_id;
            $winchDriver->save();
            
            return Response()->json(['msg' => 'Winch Driver edited Successfully'], 200);
        }
    }

    public function userManageCarService($request,$role,$checkNewRole,$checkProvider,$serviceProvider){
        $carsServiceValidator = Validator::make($request->all() , [
            // check user authorize
            'URL' => 'nullable|url'
        ]);
        // validate Data
        if($carsServiceValidator->fails())
        {
            if($checkProvider){
                $serviceProvider->delete();
            }
            if($checkNewRole){
                $role->delete();
            }
            $errors = $carsServiceValidator->errors();
            return Response()->json($errors, 400);
        }
        
        if($request->roleID == null){ // means add
            $carService = new Carservice;
            $carService->role_id = $role->id;
        }
        else{ // means edit
            $carService = Carservice::where('role_id',$role->id)->first();
        }
        $carService->URL = $request->URL;
        $carService->servicetype = $request->type;
        $carService->save();
                
        if($carService){
            if($request->type == 2){
                return $this->userManageWorkShop($request,$carService,$role,$checkNewRole,$checkProvider,$serviceProvider);
            }
            else if($request->type == 3){
                return $this->userManageSparePartShop($request,$carService,$role,$checkNewRole,$checkProvider,$serviceProvider);
            }
        }
        else{
            if($checkProvider){
                $serviceProvider->delete();
            }
            if($checkNewRole){
                $role->delete();
            }
            return Response()->json(['msg' => 'Error: Please try again'], 300);
        }
    }

    public function userManageWorkShop($request,$carService,$role,$checkNewRole,$checkProvider,$serviceProvider){
        $workshopValidator = Validator::make($request->all() , [
            "workshoptype"    => "required|array|min:1",
            'workshoptype.*' => 'required|distinct|exists:worshop_supervisor_type,id'
        ]);
        // validate Data
        if($workshopValidator->fails())
        {
            if($checkProvider){
                $serviceProvider->delete();
            }
            if($checkNewRole){
                $role->delete();
            }
            
            $errors = $workshopValidator->errors();
            return Response()->json($errors, 400);
        }
        if($request->roleID == null){ // means add
            $workshop = new Workshop;
            $workshop->carservice_id = $carService->id;
            $result = $workshop->save();

           if($result){
               foreach($request->workshoptype as $ShopType){
                    $workshop->Workshoptype()->create([
                        'workshop_id' => $workshop->id,
                        'workshoptype' => $ShopType
                    ]);             
               }
                $workshop->save();
                return Response()->json(['msg' => 'Workshop added Successfully'], 200);
           }
           else{
                if($checkProvider){
                    $serviceProvider->delete();
                }
                if($checkNewRole){
                    $role->delete();
                }
                return Response()->json(['msg' => 'Error: Please try again'], 300);
           }
            
        }
        else{ // means edit
        
            $workshop = Workshop::where('carservice_id',$carService->id)->first();
            $del = $workshop->Workshoptype()->delete();
            if($del){
                foreach($request->workshoptype as $ShopType){
                    $workshop->Workshoptype()->create([
                        'workshop_id' => $workshop->id,
                        'workshoptype' => $ShopType
                    ]);             
               }
                $workshop->save();
                return Response()->json('Workshop edited Successfully', 200);
            }
            else{
                if($checkProvider){
                    $serviceProvider->delete();
                }
                if($checkNewRole){
                    $role->delete();
                }
               return Response()->json('Error: Please try again', 300);
            }
 
        }
    }

    public function userManageSparePartShop($request,$carService,$role,$checkNewRole,$checkProvider,$serviceProvider){
        $sparePartValidator = Validator::make($request->all() , [
            "spareshoptype" => ['required', Rule::in([0, 1])], // 0 normal shop , 1 agent shop
        ]);
        // validate Data
        if($sparePartValidator->fails())
        {
            if($checkProvider){
                $serviceProvider->delete();
            }
            if($checkNewRole){
                $role->delete();
            }
            
            $errors = $sparePartValidator->errors();
            return Response()->json($errors, 400);
        }
        if($request->roleID == null){ // means add
            $spareShop = new Sparesshop;
            $spareShop->carservice_id = $carService->id;
            $spareShop->spareshoptype = $request->spareshoptype;
            $spareShop->save();
            return Response()->json(['msg' => 'Spare Shop added Successfully'], 200);
        }
        else{ // means edit
            $spareShop = Sparesshop::where('carservice_id',$carService->id)->first();
            $spareShop->spareshoptype = $request->spareshoptype;
            $spareShop->save();
            return Response()->json(['msg' => 'Spare Shop edited Successfully'], 200);
        } 
    }
 
    public function checkHasWinchDriver(Request $request){
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
        
        if($user->Serviceprovider->id == null){
            return Response()->json(false, 200);
        }
        else{
            $checkwinch = Role::where('serviceprovider_id',$user->Serviceprovider->id)->where('type',0)->count();
            if($checkwinch >= 1){
                  return Response()->json(true, 200);  
            }
            return Response()->json(false, 200);
        }

    }
    
     // Cms Function
    public function RolesCms() {
        return view('Roles.index');
    }
    public function getRolesAjax () {
        $AllDaTa= Role::all();
        $Roles = array();
        $x = 0;
        foreach ($AllDaTa as $Role) {
            // check date first if set
            $created_at = new Carbon($Role->created_at);
            $date = new \DateTime($created_at);
            $created_at = $date->format('m/d/Y');

            $updated_at = new Carbon($Role->updated_at);
            $date = new \DateTime($updated_at);
            $updated_at = $date->format('m/d/Y');

            $Roles[$x]['id'] = $Role->id;
            $Roles[$x]['name'] = $Role->name;
            $Roles[$x]['description'] = $Role->description;
            $Roles[$x]['type'] = $Role->type;
            $Roles[$x]['lock'] = $Role->lock;
            $Roles[$x]['created_at'] = $created_at;
            $Roles[$x]['updated_at'] = $updated_at;

            $path = 'http://localhost:8000/api/storage/';
            $serverpath = 'http://www.drivixcorp.com/api/storage/';
            if(isset($Role->Roleimgs) && isset($Role->Roleimgs[0])) {
                $response = $serverpath .$Role->Roleimgs[0]->image.'/RolesImgs';
                $Roles[$x]['logo'] = $response;
            } else {
                $response = $serverpath .'role.png'.'/RolesImgs';
                $Roles[$x]['logo'] = $response;
            }
            $x++;
        }
        $data = collect($Roles);
        return Datatables::of($data)->setRowClass(function($p) {
            return (($p['lock'] === 0) ? 'locked-row text-center' : 'unlocked-row text-center');
        })->make(true);
    }
    public function lockAunlockRoles (Request $request) {
        $role = Role::find($request->id);
        if(isset($role)) {
            if($role->lock ==1) {
                $role->lock = 0;
            } else { $role->lock = 1 ;}
            $role->save();
            return 'true';
        }
        return 'false';
    }
    public function getRoleCms ($id) {
        $role = Role::find($id);
        if(isset($role)) {
            if($role->type == 0) {$role->type = 'winch driver';}
            if($role->type == 1) {$role->type = 'winch company';}
            if($role->type == 2) {$role->type = 'workshop';}
            if($role->type == 3) {$role->type = 'Spares shop';}
            $role->images = $role->Roleimgs;
            $AllPhones = $role->Rolephone;
            $listPhones = [];
            foreach ($AllPhones as $phone) {
                $listPhones [] = $phone->phone;
            }
            $role->phones = implode(' , ', $listPhones);

            $AllLocations = $role->Rolelocation;
            $listLocations = [];
            foreach ($AllLocations as $locate) {
                $listLocations [] = $locate->location;
            }
            $role->locations = implode(' , ', $listLocations);

            return view('Roles.show' , compact('role'));
        }
        else {
            Session::flash('warning','this Role is not exists any more !!');
            return redirect('/manage-roles');
        }

    }
}
