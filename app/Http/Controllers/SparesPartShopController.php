<?php

namespace App\Http\Controllers;

use App\Sparesshop;
use App\Carservice;
use App\Role;
use App\Rolelocation;
use Illuminate\Http\Request;
use DB;
use App\User;
use App\Estimate;
use Illuminate\Support\Facades\Validator;

class SparesPartShopController extends Controller
{
     public $SparesParts = [];
    public $nextPageToken = '';

    /********* Apis *********/
    public function googleMapApWorkShop() {
        // data needed for api
        $URl = 'https://maps.googleapis.com/maps/api/place/textsearch/json?key=AIzaSyAb1qML5aW84D-NJg4bnu3YPoFyNlZ387E&query=car+shop+in+egypt&types=store';
        //Send an asynchronous request.
        $count = 0;
        do {
            $client = new \GuzzleHttp\Client();
            if ($count == 0) {
                $request = new \GuzzleHttp\Psr7\Request('GET', $URl);
            } else {
                $newUrl = $URl . '&pagetoken=' . $this->nextPageToken;
                $request = new \GuzzleHttp\Psr7\Request('GET', $newUrl);
            }

            $promise = $client->sendAsync($request)->then(function ($response) use ($count, $URl) {
                $data = json_decode($response->getBody());
                foreach ($data->results as $MapItem) {
                    $this->workshops [] = ['name' => $MapItem->name, 'lat' => $MapItem->geometry->location->lat, 'lng' => $MapItem->geometry->location->lng, 'address' => $MapItem->formatted_address, 'rate' => $MapItem->rating, 'icon' => $MapItem->icon];
                }
                if (isset($data->next_page_token)) {
                    $this->nextPageToken = $data->next_page_token;
                } else {
                    $this->nextPageToken = false;
                }
                sleep(1);
            });
            $promise->wait();
            $count++;
        } while ($this->nextPageToken != false);

        foreach ($this->workshops as $workshopItem) {
            // Role Save
            $Role =  new Role();
            $Role->type = 3 ;
            $Role->status = 1 ;
            $Role->name = $workshopItem['name'] ;
            $Role->lock = 0;
            $check = $Role->save();
            if($check == true)
            {
                // save Role Location
                $role_location = new Rolelocation();
                $role_location->role_id = $Role->id;
                $role_location->location = $workshopItem['address'];
                $role_location->long = $workshopItem['lng'];
                $role_location->lat = $workshopItem['lat'];
                $check_location = $role_location->save();
                if($check_location == true) {
                    // save car service object
                    $car_service = new Carservice();
                    $car_service->role_id = $Role->id;
                    $car_service->servicetype = 3 ;
                    $check_car_service = $car_service->save();
                    if ($check_car_service == true) {
                        $spares_Shop = new Sparesshop();
                        $spares_Shop->carservice_id = $car_service->id;
                        $spares_Shop->save();
                    }
                    else {
                        dd('error car service');
                    }
                }
                else {
                    dd('fail role location') ;
                }
            }
            else {
                dd('failed role');
            }
            // Services Information

        }
    }
    public function NearestSparesPartShop(Request $request) {
        if (!isset($request->lat) || !isset($request->long)) {
            return response()->json(['msg' => 'latitude and longitude is required'], 400);
        } else {
            $nearestLocation = DB::table("rolelocation")
                ->select("rolelocation.role_id" , "rolelocation.location" , "rolelocation.lat" , "rolelocation.long" ,"role.name" , "role.description" , "role.work_from" , "role.work_to" , "role.workingdays"
                    , DB::raw("6371 * acos(cos(radians(" . $request->lat . ")) 
                            * cos(radians(rolelocation.lat)) 
                            * cos(radians(rolelocation.long) - radians(" . $request->long . ")) 
                            + sin(radians(" . $request->lat . ")) 
                            * sin(radians(rolelocation.lat))) AS distance"))
                ->join('role',function ($join) {
                    $join->on('rolelocation.role_id', '=', 'role.id')
                        ->where('role.type', '=', '3')
                        ->where ('role.lock' , '1');
                })
                ->limit(10)
                ->orderBy('distance', 'asc')
                ->get();

            // get reviews
            foreach($nearestLocation as $role) {

                $getRole = Role::find($role->role_id);
                $role->Rolephone = $getRole->Rolephone;
                $AllImgs = $getRole->Roleimgs;
                $role->Rolelocation =  $getRole->Rolelocation;
                foreach ($AllImgs as $img) {
                    $img->image = 'http://www.drivixcorp.com/api/storage/' . $img->image . '/RolesImgs';
                }
                $role->Roleimgs = $AllImgs;
                $role_id = $role->role_id ;
                $number_of_drivixs_rate = 0;
                $total_drivix_rate = (float) 0.0;

                $car_service = Carservice::where('role_id' , $role_id)->first();
                $reviews = $car_service->Estimate;
                $AllReviewToWorkshop = [];
                foreach($reviews as $review) {
                    $AllReviewToWorkshop[] = ['username' => $review->user->name ,  'rate' => $review->stars];
                    $number_of_drivixs_rate ++ ;
                    $total_drivix_rate += (float) $review->stars;
                }
                $role->review = $AllReviewToWorkshop;
                $my_review = [];
                if ($request->header('token')) {
                    $user = User::where('token' , $request->header('token') ) ->first();
                    if ( $user ) {
                        $my_review = Estimate::where('carservice_id' , $car_service->id)->where('User_id' , $user->id )->first();
                    }
                }
                $role->my_review = $my_review;
                $role->num_Drivix_review = $number_of_drivixs_rate;
                if($number_of_drivixs_rate != 0)
                    $role->Drivix_rate = number_format((float)($total_drivix_rate/ $number_of_drivixs_rate), 2, '.', '');
                else
                    $role->Drivix_rate= 0.00;
            }
            return response()->json($nearestLocation, 200);
        }
    }
    public function FilterSparesPartShop(Request $request) {
        $validator = Validator::make($request->all() , [
            'text' => 'required|string'
        ]);
        if($validator->fails())
        {
            $errors = $validator->errors();
            return Response()->json($errors, 400);
        }
        // get Params
        if(isset($request->text))
        {
            $findWorkShops = Role::where('name' , 'LIKE' , "%{$request->text}%")->where('type' , '3')->where('lock','1')->limit(10)->get();
            foreach ($findWorkShops as $workshop) {
                $workshop->Rolephone;
                $AllImgs = $workshop->Roleimgs;
                foreach ($AllImgs as $img) {
                    $img->image = 'http://www.drivixcorp.com/api/storage/' . $img->image . '/RolesImgs';
                }
                $workshop->Rolelocation;
            }
            foreach($findWorkShops as $role) {
                $role_id = $role->id ;
                $number_of_drivixs_rate = 0;
                $total_drivix_rate = (float) 0.0;
                $car_service = Carservice::where('role_id' , $role_id)->first();
                $reviews = $car_service->Estimate;
                $AllReviewToWorkshop = [];
                foreach($reviews as $review) {
                    $AllReviewToWorkshop[] = ['username' => $review->user->name ,  'rate' => $review->stars];
                    $number_of_drivixs_rate ++ ;
                    $total_drivix_rate += (float) $review->stars;
                }
                $role->review = $AllReviewToWorkshop;
                $my_review = [];
                if ($request->header('token')) {
                    $user = User::where('token' , $request->header('token') ) ->first();
                    if ( $user ) {
                        $my_review = Estimate::where('carservice_id' , $car_service->id)->where('User_id' , $user->id )->first();
                    }
                }
                $role->my_review = $my_review;
                $role->num_Drivix_review = $number_of_drivixs_rate;
                if($number_of_drivixs_rate != 0)
                    $role->Drivix_rate = number_format((float)($total_drivix_rate/ $number_of_drivixs_rate), 2, '.', '');
                else
                    $role->Drivix_rate= 0.00;
            }
            return response()->json($findWorkShops , 200) ;
        }
        else
        {
            return response()->json([] , 200);
        }
    }
}
