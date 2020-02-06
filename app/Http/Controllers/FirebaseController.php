<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Validator;
use App\User;
use Exception;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
use Google\Cloud\Firestore\FirestoreClient;
class FirebaseController extends Controller
{

    public function index(Request $request)
    {
        /*try {
            $validate = Validator::make($request->all(), [
                'token' => 'required|string|exists:users,token',
                'message' => 'required|string',

            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 503);
            }
            $user = User::where('token', $request->token)->first();
            if ($user) {

                $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/drivix-2906e-firebase-adminsdk-7hbno-6215cd148f.json');
                $firebase 		  = (new Factory)
                                ->withServiceAccount($serviceAccount)
                                ->withDatabaseUri('https://drivix-2906e.firebaseio.com/')
                                ->create();
                                $date = date_create();
$time=date_timestamp_get($date);
                $database 		= $firebase->getDatabase();
                $newPost 		  = $database
                                    ->getReference('messages/')
                                    ->push(['key'=>$time,'token'=> $request->token,'title' => $user->name,'body' => $request->message]);
                //print_r($newPost->getvalue());
                return response()->json($newPost->getvalue(), 201);

                      }
                              else {
                                return response()->json(['msg' => 'Unauthorized!'], 400);
                            }
        }

        catch(Exception $ex)
        {
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 400);

        }

*/
$db = new FirestoreClient();

$citiesRef = $db->collection('messages');
//$query = $citiesRef->where('state', '=', 'CA');
$snapshot = $citiesRef->documents();
foreach ($snapshot as $document) {
    printf('Document %s returned by query state=CA' . PHP_EOL, $document->id());
}
    }
    public function storedriver(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'token' => 'required|string|exists:users,token',

            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 503);
            }
            $user = User::where('token', $request->token)->first();
            if ($user) {
                $driver = $user->Serviceprovider->Role;
                if (!$driver->isEmpty()) {
                    foreach ($driver as $obj) {
                        if (empty($obj->Winchdriver)) {
                            return Response()->json('no driver with that id', 404);
                        }
                        $action= $obj->Winchdriver;
                        break;
                    }
                    if ($action->availability==1) {
                        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/drivix-2906e-firebase-adminsdk-7hbno-6215cd148f.json');
                        $firebase 		  = (new Factory)
                                ->withServiceAccount($serviceAccount)
                                ->withDatabaseUri('https://drivix-2906e.firebaseio.com/')
                                ->create();

                        $database 		= $firebase->getDatabase();
                        $latitude=$driver->where('type',0)->first()->Rolelocation->first()->lat;
                        $longtude=$driver->where('type',0)->first()->Rolelocation->first()->long;
                        if ($longtude&&$latitude) {
                            $newPost 		  = $database
                                    ->getReference('Winch/'.$user->id)
                                    ->set(['Id'=>$user->id,'driver' => $request->token,'lat'=>$latitude,'long'=>$longtude ]);
                                    return response()->json($newPost->getvalue(), 201);

                                }
                        else
                        {
                            return response()->json(['msg' => 'No Role LOcation were added!'], 404);

                        }
                    }
                    else
                {
                    return response()->json(['msg' => 'Not Available!'], 400);
                }
                }
                else
                {
                    return response()->json(['msg' => 'Unauthorized!'], 400);
                }
            }
                              else {
                                return response()->json(['msg' => 'Unauthorized!'], 400);
                            }
        }

        catch(Exception $ex)
        {
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 400);

        }


    }
    public function updatedriver(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'token' => 'required|string|exists:users,token',
                'lat' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
                'long' => ['required', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 503);
            }
            $user = User::where('token', $request->token)->first();
            if ($user) {
                $driver = $user->Serviceprovider->Role;
                if (!$driver->isEmpty()) {
                    foreach ($driver as $obj) {
                        if (empty($obj->Winchdriver)) {
                            return Response()->json('no driver with that id', 404);
                        }
                        $action= $obj->Winchdriver;
                        break;
                    }
                    if ($action->availability==1) {
                        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/drivix-2906e-firebase-adminsdk-7hbno-6215cd148f.json');
                        $firebase 		  = (new Factory)
                                ->withServiceAccount($serviceAccount)
                                ->withDatabaseUri('https://drivix-2906e.firebaseio.com/')
                                ->create();
                        $database 		= $firebase->getDatabase();
                        $newPost 		  = $database
                                    ->getReference('Winch/'.$user->id)
                                    ->set(['Id'=>$user->id,'driver' => $request->token,'lat'=>$request->lat,'long'=>$request->long ]);
                        echo"<pre>";
                        //print_r($newPost->getvalue());
                        return response()->json($newPost->getvalue(), 201);
                    }
                    else
                {
                    return response()->json(['msg' => 'Not Available!'], 400);
                }
                }
                else
                {
                    return response()->json(['msg' => 'Unauthorized!'], 400);
                }
            }
                              else {
                                return response()->json(['msg' => 'Unauthorized!'], 400);
                            }
        }

        catch(Exception $ex)
        {
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 400);

        }


    }

    public function deletedriver(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'token' => 'required|string|exists:users,token',

            ]);
            if ($validate->fails()) {
                $errors = $validate->errors();
                return Response()->json($errors, 503);
            }
            $user = User::where('token', $request->token)->first();
            if ($user) {
                $driver = $user->Serviceprovider->Role;
                if (!$driver->isEmpty()) {
                    foreach ($driver as $obj) {
                        if (empty($obj->Winchdriver)) {
                            return Response()->json('no driver with that id', 404);
                        }
                        //$action= $obj->Winchdriver;
                        break;
                    }
                        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/drivix-2906e-firebase-adminsdk-7hbno-6215cd148f.json');
                        $firebase 		  = (new Factory)
                                ->withServiceAccount($serviceAccount)
                                ->withDatabaseUri('https://drivix-2906e.firebaseio.com/')
                                ->create();
                        $database 		= $firebase->getDatabase();
                        $addloc 		  = $database
                        ->getReference('Winch/'.$user->id);
                        $driver->where('type',0)->first()->Rolelocation->first()->lat=$addloc->getvalue()['lat'];
                        $driver->where('type',0)->first()->Rolelocation->first()->long=$addloc->getvalue()['long'];
                        $driver->where('type',0)->first()->Rolelocation->first()->save();
                        $newPost 		  = $database
                                    ->getReference('Winch/'.$user->id)
                                    ->set([null]);
                        echo"<pre>";
                        //print_r($newPost->getvalue());
                        return response()->json($newPost->getvalue(), 201);


                }
                else
                {
                    return response()->json(['msg' => 'Unauthorized!'], 400);
                }
            }
                              else {
                                return response()->json(['msg' => 'Unauthorized!'], 400);
                            }
        }

        catch(Exception $ex)
        {
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 400);

        }


    }
    public function viewdriver(Request $request)
    {
        try {

                        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/drivix-2906e-firebase-adminsdk-7hbno-6215cd148f.json');
                        $firebase 		  = (new Factory)
                                ->withServiceAccount($serviceAccount)
                                ->withDatabaseUri('https://drivix-2906e.firebaseio.com/')
                                ->create();
                        $database 		= $firebase->getDatabase();
                        $newPost 		  = $database
                                    ->getReference('Winch/');
                        echo"<pre>";
                        //print_r($newPost->getvalue());
                        return response()->json($newPost->getvalue(), 201);



        }

        catch(Exception $ex)
        {
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 400);

        }


    }
    public function getmessage(Request $request)
    {
        try {

                        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/drivix-2906e-firebase-adminsdk-7hbno-6215cd148f.json');
                        $firebase 		  = (new Factory)
                                ->withServiceAccount($serviceAccount)
                                ->withDatabaseUri('https://drivix-2906e.firebaseio.com/')
                                ->create();
                        $database 		= $firebase->getDatabase();
                        $newPost 		  = $database
                                    ->getReference('messages/');
                                    $arr=$newPost->getvalue();
                                    $arr=end($arr);
                        return response()->json($arr, 201);



        }

        catch(Exception $ex)
        {
            return response()->json(['msg' => 'error!' .$ex->getmessage()], 400);

        }


    }
//.removeValue()
   }
