<?php

namespace App\Http\Controllers;

use App\Sitesettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class SiteSetting extends Controller
{
    //
    function index () {
        $Settings = Sitesettings::all();
        return view('siteSetting.index' , compact('Settings'));
    }
    function update (Request $request) {
        // update method
        $setting = Sitesettings::find($request->set_id);
        if($setting) {
            $setting->value = $request->value;
            $setting->save();
            Session::flash('Success','Settings Updated Successfully');
            return redirect()->back();
        }
        else {
            Session::flash('warning','Failed to update Setting , pls try again later !!');
            return redirect()->back();
        }
    }
    function updateLogo (Request $request) {
        // update method
        $setting = Sitesettings::find($request->set_id);
        if($setting) {
            // check image then save it
            $imageName = $this->saveImage($request , 'siteSetting');
            if($imageName == false) {
                $imageName= 'http://www.drivixcorp.com/api/storage/Logo-yello.png/siteSetting';
                $setting->value = $imageName;
                $setting->save();
                Session::flash('warning','Failed to update Logo , pls try again later !!');
                return redirect()->back();
            }
            else {
                $response = 'http://www.drivixcorp.com/api/storage/'. $imageName.'/siteSetting';
                $setting->value = $response;
                $setting->save();
                // return success message
                Session::flash('Success','Settings Updated Successfully');
                return redirect()->back();
            }
        }
        else {
            Session::flash('warning','Failed to update Setting , pls try again later !!');
            return redirect()->back();
        }
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
}
