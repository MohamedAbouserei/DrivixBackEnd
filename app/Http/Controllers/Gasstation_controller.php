<?php

namespace App\Http\Controllers;
use App\Gasstation;
use App\gasStationReview;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use File;
use Redirect;
use Validator;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Response;

class Gasstation_controller extends Controller
{

    public $GasStation = [];
    public $nextPageToken = '';

    /********* Apis *********/
    public function googleMapApiGasStation($city)
    {
        // data needed for api
        $URl = 'https://maps.googleapis.com/maps/api/place/textsearch/json?key=AIzaSyAb1qML5aW84D-NJg4bnu3YPoFyNlZ387E&query=gas+station+in+' . $city . '+egypt&type=gas_station';

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

            $promise = $client->sendAsync($request)->then(function ($response) use ($city, $count, $URl) {
                $data = json_decode($response->getBody());
                foreach ($data->results as $MapItem) {
                    $this->GasStation [] = ['name' => $MapItem->name, 'lat' => $MapItem->geometry->location->lat, 'lng' => $MapItem->geometry->location->lng, 'address' => $MapItem->formatted_address, 'rate' => $MapItem->rating, 'icon' => $MapItem->icon];
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

        foreach ($this->GasStation as $gasStation) {
            // Main Information
            $Gasstations = new Gasstation();
            $Gasstations->name = $gasStation['name'];
            $Gasstations->lat = $gasStation['lat'];
            $Gasstations->long = $gasStation['lng'];
            $Gasstations->address = $gasStation['address'];
            $Gasstations->google_rate = $gasStation['rate'];
            $Gasstations->icon = $gasStation['icon'];
            $Gasstations->city = $city;
            // Services Information
            $Gasstations->tier_repare = 0;
            $Gasstations->blowing_air = 0;
            $Gasstations->petrol_80 = 1;
            $Gasstations->petrol_92 = 1;
            $Gasstations->petrol_95 = 1;
            $Gasstations->align_wheel = 0;
            $Gasstations->sollar = 0;
            $Gasstations->gas = 1;
            $Gasstations->car_washing = 1;
            $Gasstations->blowing_nitro = 0;
            $Gasstations->fix_suspension = 0;
            $Gasstations->oil_change = 0;
            $Gasstations->save();
        }
    }
       public function getGasStationInCity(Request $request)
    {
        // check variable
        if (!isset($request->city)) {
            return response()->json('pls enter a valid city', 400);
        }
        else {
            // check filter
            $getData = [];
            if (isset($request->filter) && ($request->filter == 'desc' || $request->filter == 'asc')) {
                $getData = Gasstation::where('city', $request->city)->orderBy('google_rate', $request->filter)->get();
            } else {
                $getData = Gasstation::where('city', $request->city)->get();
            }

            foreach ($getData as $gas) {
                // gas station id
                $gas_id = $gas->id ;
                $reviews =  DB::table("gas_station_review" , "users")
                    ->select("gas_station_review.rate" , "gas_station_review.created_at as date" , "users.name")
                    ->where('gas_id' , $gas_id)
                    ->join('users' , 'users.id' ,'gas_station_review.user_id')
                    ->get();
                $number_of_drivixs_rate = 0;
                $total_drivix_rate = (float) 0.0;
                foreach($reviews as $review) {
                    $number_of_drivixs_rate ++ ;
                    $total_drivix_rate += (float) $review->rate;
                }
                $gas->review = $reviews;
                $gas->num_Drivix_review = $number_of_drivixs_rate;
                if($number_of_drivixs_rate != 0)
                    $gas->Drivix_rate = number_format((float)($total_drivix_rate/ $number_of_drivixs_rate), 2, '.', '');
                else
                    $gas->Drivix_rate= 0.00;
                $gas->review = $reviews;
            }

            return response()->json($getData, 200);
        }
    }
    public function AllGasStations()
    {
        return response()->json(Gasstation::all(), 200);
    }
    public function NearestTenGasStations(Request $request)
    {
        if (!isset($request->lat) || !isset($request->long)) {
            return response()->json(['msg' => 'latitude and longitude is required'], 400);
        } else {
            $nearestLocation = DB::table("gasstation")
                ->select("gasstation.*"
                    , DB::raw("6371 * acos(cos(radians(" . $request->lat . ")) 
        * cos(radians(gasstation.lat)) 
        * cos(radians(gasstation.long) - radians(" . $request->long . ")) 
        + sin(radians(" . $request->lat . ")) 
        * sin(radians(gasstation.lat))) AS distance"))
                ->limit(10)
                ->orderBy('distance', 'asc')
                ->get();
            foreach ($nearestLocation as $gas) {
                // gas station id
                $gas_id = $gas->id ;
                $reviews =  DB::table("gas_station_review" , "users")
                                ->select("gas_station_review.rate" , "gas_station_review.created_at as date" , "users.name")
                                ->where('gas_id' , $gas_id)
                                ->join('users' , 'users.id' ,'gas_station_review.user_id')
                                ->get();
                $number_of_drivixs_rate = 0;
                $total_drivix_rate = (float) 0.0;
                foreach($reviews as $review) {
                    $number_of_drivixs_rate ++ ;
                    $total_drivix_rate += (float) $review->rate;
                }
                $gas->review = $reviews;
                $gas->num_Drivix_review = $number_of_drivixs_rate;
                if($number_of_drivixs_rate != 0)
                    $gas->Drivix_rate = number_format((float)($total_drivix_rate/ $number_of_drivixs_rate), 2, '.', '');
                else
                    $gas->Drivix_rate= 0.00;
                $gas->review = $reviews;
            }
            return response()->json($nearestLocation, 200);
        }
    }
    public function FilterGasStation(Request $request)
    {
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
            $findGasStations = Gasstation::where('name' , 'LIKE' , "%{$request->text}%")->limit(10)->get();
            if(isset($findGasStations))
            {
                foreach ($findGasStations as $gas) {
                // gas station id
                $gas_id = $gas->id ;
                $reviews =  DB::table("gas_station_review" , "users")
                                ->select("gas_station_review.rate" , "gas_station_review.created_at as date" , "users.name")
                                ->where('gas_id' , $gas_id)
                                ->join('users' , 'users.id' ,'gas_station_review.user_id')
                                ->get();
                $number_of_drivixs_rate = 0;
                $total_drivix_rate = (float) 0.0;
                foreach($reviews as $review) {
                    $number_of_drivixs_rate ++ ;
                    $total_drivix_rate += (float) $review->rate;
                }
                $gas->review = $reviews;
                $gas->num_Drivix_review = $number_of_drivixs_rate;
                if($number_of_drivixs_rate != 0)
                    $gas->Drivix_rate = number_format((float)($total_drivix_rate/ $number_of_drivixs_rate), 2, '.', '');
                else
                    $gas->Drivix_rate = 0.00;
                }
            }
            return response()->json($findGasStations , 200) ;
        }
        else
        {
            return response()->json([] , 200);
        }
    }
    public function makeReview(Request $request)
    {
        // check data before continue
        $validator = Validator::make($request->all() , [
            'token' => 'required|string|exists:users,token' ,
            'gas_id' => 'required|numeric|exists:gasstation,id' ,
            'rate' => ['required' , Rule::in(['1', '2' , '3' , '4' , '5'])] ,
        ]);
        // validate Data
        if($validator->fails())
        {
            $errors = $validator->errors();
            return Response()->json($errors, 400);
        }
        // get user
        $user = User::where('token' , $request->token)->first();
        // check if user already review this gas station
        $check_review = $user->gas_review->where('gas_id' , $request->gas_id)->first();
        if($check_review)
        {
            // already make review then update it
            $check_review->rate = $request->rate;
            $check_review->save();
            return Response()->json(['your review Updated Successfully'], 200);
        }
        else
        {
            // create a new review
            $review = new gasStationReview();
            $review->gas_id = $request->gas_id;
            $review->user_id = $user->id;
            $review->rate = $request->rate;
            $review->save();
            return Response()->json(['your review Added Successfully'], 200);
        }
    }
    public function getUserReview(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'token' => 'required|string|exists:users,token' ,
            'gas_id' => 'required|numeric|exists:gasstation,id' ,
        ]);
        // validate Data
        if($validator->fails())
        {
            $errors = $validator->errors();
            return Response()->json($errors, 400);
        }
        $user = User::where('token' , $request->token)->first();
        $check_review = $user->gas_review->where('gas_id' , $request->gas_id)->first();
        if($check_review)
        {
            return response()->json($check_review->rate,200);
        }
        else
        {
            return response()->json([],200);
        }
    }

    /*********** Cms ***********/
    public function GasStationCms()
    {
        return view('gasStation.index');
    }
    public function getGasStationAjax()
    {

        $AllDaTa= Gasstation::all();
        $allGasStations = array();
        $x = 0;
        foreach ($AllDaTa as $gas) {
            // check date first if set
            $type = null;
            $created_at = new Carbon($gas->created_at);
            $date = new \DateTime($created_at);
            $created_at = $date->format('m/d/Y');

            $updated_at = new Carbon($gas->updated_at);
            $date = new \DateTime($updated_at);
            $updated_at = $date->format('m/d/Y');

            $allGasStations[$x]['id'] = $gas->id;
            $allGasStations[$x]['name'] = $gas->name;
            $allGasStations[$x]['icon'] = $gas->icon;
            $allGasStations[$x]['city'] = $gas->city;
            $allGasStations[$x]['created_at'] = $created_at;
            $allGasStations[$x]['updated_at'] = $updated_at;
            $x++;
        }
        $data = collect($allGasStations);
        return Datatables::of($data)->make(true);
    }
    public function storeGasStation ( Request $request) {
        $request->validate([
              "name" => "string|required" ,
              "city" => "string|required" ,
              "tier" => "string" ,
              "blow-air" => "string" ,
              "p-80" => "string" ,
              "p-92" => "string" ,
              "p-95" => "string" ,
              "a-wheel" => "string" ,
              "solar" => "string" ,
              "gas" => "string" ,
              "car-washing" => "string" ,
              "p-nitro" => "string" ,
              "fix-sus" => "string" ,
              "o-change" => "string" ,
              "address" => "string|required" ,
              "lat" => "required|numeric" ,
              "long" => "required|numeric" ,
              "image" => "max:2048"
        ]);
        $imageName = $this->saveImage($request , 'gasStation');
        if($imageName == false) {
            $imageName= null;
        }
        else {
            $response = 'http://www.drivixcorp.com/api/storage/'.$imageName.'/gasStation';
            $imageName =  $response;
        }
        // save new gas station
        $gasStation = new Gasstation();
        $gasStation->name = $request->name;
        $gasStation->city = $request->city;
        $gasStation->address = $request->address;
        $gasStation->lat = $request->lat;
        $gasStation->long = $request->long;
        $gasStation->icon = $imageName;

        if(isset($request->tier)) {$gasStation->tier_repare = 1;} else {$gasStation->tier_repare = 0;}
        if(isset($request->blowair)) {$gasStation->blowing_air = 1;}  else {$gasStation->blowing_air = 0;}
        if(isset($request->p80)) {$gasStation->petrol_80 = 1;}  else {$gasStation->petrol_80 = 0;}
        if(isset($request->p92)) {$gasStation->petrol_92 = 1;}  else {$gasStation->petrol_92 = 0;}
        if(isset($request->p95)) {$gasStation->petrol_95 = 1;}  else {$gasStation->petrol_95 = 0;}
        if(isset($request->awheel)) {$gasStation->align_wheel = 1;}  else {$gasStation->align_wheel = 0;}

        if(isset($request->solar)) {$gasStation->sollar = 1;} else {$gasStation->sollar = 0;}
        if(isset($request->gas)) {$gasStation->gas = 1;} else {$gasStation->gas = 0;}
        if(isset($request->carwashing)) {$gasStation->car_washing = 1;} else {$gasStation->car_washing = 0;}
        if(isset($request->pnitro)) {$gasStation->blowing_nitro = 1;} else {$gasStation->blowing_nitro = 0;}
        if(isset($request->fixsus)) {$gasStation->fix_suspension = 1;} else {$gasStation->fix_suspension = 0;}
        if(isset($request->ochange)) {$gasStation->oil_change = 1;} else {$gasStation->oil_change = 0;}

        $gasStation->save();
        Session::flash('success','your Gas Station Added successfully');
        return redirect()->route('gasStation');
    }
    public function deleteGasStation (Request $request) {

        $id = $request->id;
        $obj = Gasstation::find($id);
        if($obj)
        {
            $obj->delete();
            return 'true';
        }
        else
        {
            return 'false';
        }
    }
    public function AddGasStation (){
        return view('gasStation.add');
    }
    function saveImage($request, $type) {

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $name = time(). $request->name .'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs/'.$type);
            $image->move($destinationPath, $name);
            return $name;
        }
        else {
            return false;
        }

    }
    function EditGasStationCms ($id) {
        $gasStation = Gasstation::find($id);
        if(isset($gasStation)) {
            return view('gasStation.Edit' , compact('gasStation'));
        }
        else {
            Session::flash('warning','this gas station is not exists any more !!');
            return redirect()->route('gasStation');
        }
    }
    function updateGasStationCms (Request $request) {
        $request->validate([
            "name" => "string|required" ,
            "city" => "string|required" ,
            "tier" => "string" ,
            "blow-air" => "string" ,
            "p-80" => "string" ,
            "p-92" => "string" ,
            "p-95" => "string" ,
            "a-wheel" => "string" ,
            "solar" => "string" ,
            "gas" => "string" ,
            "car-washing" => "string" ,
            "p-nitro" => "string" ,
            "fix-sus" => "string" ,
            "o-change" => "string" ,
            "address" => "string|required" ,
            "lat" => "required|numeric" ,
            "long" => "required|numeric" ,
            "image" => "max:2048"
        ]);
        $gasStation = Gasstation::find($request->id);
        // check image
        $imageName = $this->saveImage($request , 'gasStation');
        if($imageName != false) {
            if(isset($gasStation->icon) && $gasStation->icon != 'http://www.drivixcorp.com/api/storage/gas_station.png/gasStation') {
                $oldImageName = explode('/' , $gasStation->icon);
                $oldImageName = ($oldImageName[count($oldImageName)-2]);
                File::delete(public_path() . '/imgs/gasStation/' . $oldImageName);
            }
            $response = 'http://www.drivixcorp.com/api/storage/'.$imageName.'/gasStation';
            $gasStation->icon = $response;
        }
        // save new gas station
        $gasStation->name = $request->name;
        $gasStation->city = $request->city;
        $gasStation->address = $request->address;
        $gasStation->lat = $request->lat;
        $gasStation->long = $request->long;

        if(isset($request->tier)) {$gasStation->tier_repare = 1;} else {$gasStation->tier_repare = 0;}
        if(isset($request->blowair)) {$gasStation->blowing_air = 1;}  else {$gasStation->blowing_air = 0;}
        if(isset($request->p80)) {$gasStation->petrol_80 = 1;}  else {$gasStation->petrol_80 = 0;}
        if(isset($request->p92)) {$gasStation->petrol_92 = 1;}  else {$gasStation->petrol_92 = 0;}
        if(isset($request->p95)) {$gasStation->petrol_95 = 1;}  else {$gasStation->petrol_95 = 0;}
        if(isset($request->awheel)) {$gasStation->align_wheel = 1;}  else {$gasStation->align_wheel = 0;}

        if(isset($request->solar)) {$gasStation->sollar = 1;} else {$gasStation->sollar = 0;}
        if(isset($request->gas)) {$gasStation->gas = 1;} else {$gasStation->gas = 0;}
        if(isset($request->carwashing)) {$gasStation->car_washing = 1;} else {$gasStation->car_washing = 0;}
        if(isset($request->pnitro)) {$gasStation->blowing_nitro = 1;} else {$gasStation->blowing_nitro = 0;}
        if(isset($request->fixsus)) {$gasStation->fix_suspension = 1;} else {$gasStation->fix_suspension = 0;}
        if(isset($request->ochange)) {$gasStation->oil_change = 1;} else {$gasStation->oil_change = 0;}

        $gasStation->save();
        Session::flash('success','your Gas Station Added successfully');
        return redirect()->back();
    }
}

