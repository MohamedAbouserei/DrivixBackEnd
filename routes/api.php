<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('users', 'Usersapi@showuser');
Route::get('deleteuser/{id}', 'Usersapi@deleteuser');
Route::match(['get', 'post'], '/updateuser/{id}', 'Usersapi@updateuser');
Route::match(['get', 'post'], '/adduser', 'Usersapi@adduser');
//add one more gas staiotn
Route::post('/addgasstation', 'Gasstation_controller@addgasstation');
//update gas station
Route::post('/updategasstation/{id}', 'Gasstation_controller@updategasstation');
//delete one gas station
Route::get('/deletegasstation/{id}', 'Gasstation_controller@deletegasstation');
//show all gas station
Route::get('/showgasstations', 'Gasstation_controller@showgasstations');
//show specific gas station
Route::get('/specificgasstation/{id}', 'Gasstation_controller@specificgasstation');
//get one gas station cooridnates and save those coordinates to the DB
Route::get('/gasstationcoordinates/{id}', 'Gasstation_controller@gasstationcoordinates');
//add one more view on a specific gas station
Route::get('/addreview/{id}', 'Gasstation_controller@addreview');

Route::post('/profile/{token}', 'Profileapi@showprofile');
Route::post('/profiles', 'Profileapi@showallprofiles');
Route::post('deleteprofile', 'Profileapi@deleteprofile');
Route::match(['get', 'post'], '/updateprofile/{token}', 'Profileapi@updateprofile');
Route::match(['get', 'post'], '/addprofile', 'Profileapi@addprofile');
Route::post('/setImage', 'Profileapi@SetImage');
Route::post('/deleteimage', 'Profileapi@deletepic');
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
Route::get('/storage/{filename}/{foldername}', function ($filename, $foldername) {
    // Add folder path here instead of storing in the database.
    $path = public_path() . '/imgs/' . $foldername . '/' . $filename;
    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = 'png';

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

// car apis
Route::post('storecar', 'CarController@store');
Route::post('updatecar', 'CarController@update');
Route::post('deletecar', 'CarController@destroy');
Route::post('getUserCar', 'CarController@index');

/*Gas Station Apis*/
Route::post('getAllGasStations/{city}', 'Gasstation_controller@googleMapApiGasStation');
Route::get('getGasStationInCityApi', 'Gasstation_controller@getGasStationInCity');
Route::get('AllGasStations', 'Gasstation_controller@AllGasStations');
Route::get('NearestGasStation', 'Gasstation_controller@NearestTenGasStations');
Route::get('FilterGasStation', 'Gasstation_controller@FilterGasStation');
Route::post('gasStationReview' , 'Gasstation_controller@makeReview');
Route::get('getUserReview' , 'Gasstation_controller@getUserReview');

/* user Manage roles APIs  */
Route::post('userManageRole', 'RoleController@userManageRole');
Route::post('checkHasWinch', 'RoleController@checkHasWinchDriver');
Route::post('userManageRolePhone', 'RoleAccessoriesController@userManageRolePhone');
Route::post('deleteRolePhone', 'RoleAccessoriesController@deleteRolePhone');
Route::post('changeLock', 'RoleAccessoriesController@changeLock');
Route::post('changeStatus', 'RoleAccessoriesController@changeStatus');
Route::post('changeAvaliability', 'winchDriverController@changeAvaliability');
Route::post('getProviderRoles', 'RoleAccessoriesController@getProviderRoles');
Route::post('getProviderRoleDetails', 'RoleAccessoriesController@getProviderRoleDetails');
Route::post('userManageRoleLocation', 'RoleAccessoriesController@userManageRoleLocation');
Route::post('deleteRoleLocation', 'RoleAccessoriesController@deleteRoleLocation');
Route::post('providerManageCompanyBranches', 'RoleAccessoriesController@providerManageCompanyBranches');
Route::post('deleteCompanyBranches', 'RoleAccessoriesController@deleteCompanyBranches');
Route::post('getWorkshopTypes', 'RoleAccessoriesController@getWorkshopTypes');
Route::post('addRolesImages', 'RoleAccessoriesController@addRolesImages');
Route::post('deleteRolesImage', 'RoleAccessoriesController@deleteRolesImage');

/* Provider Manage Product  */
Route::post('searchProduct','productController@searchProduct');
Route::post('addproduct','productController@addproduct');
Route::post('editproduct','productController@editproduct');
Route::post('deleteproduct','productController@deleteproduct');
Route::post('GetSpareShopProdcut','productController@myproducts');
Route::post('addProductsImages','productController@addProductsImages');
Route::post('deleteProductsImage','productController@deleteProductsImage');

// work shop apis
/* this route is prohibited routed , copy right to Al3amed XDD Route::get('getAllWorkshop', 'workshopController@googleMapApWorkShop'); */
Route::get('NearestWorkshop', 'workshopController@NearestWorkShop');
Route::get('FilterWorkShop', 'workshopController@FilterWorkShop');


// spares part shop
/* this route is prohibited routed , copy right to Al3amed XDD Route::get('getAllSparesPartShop', 'SparesPartShopController@googleMapApWorkShop'); */
Route::get('NearestSparesPartShop', 'SparesPartShopController@NearestSparesPartShop');
Route::get('FilterSparesShop', 'SparesPartShopController@FilterSparesPartShop');

/* Comment APIs  */
Route::post('addComment', 'CommentController@addComment');
Route::post('editComment', 'CommentController@editComment');
Route::post('deleteComment', 'CommentController@deleteComment');
Route::post('getComments', 'CommentController@getComments');
Route::post('addEstimateComment', 'CommentController@addEstimateComment');
Route::post('deleteEstimateComment', 'CommentController@deleteEstimateComment');

/* Estimate Service APIs */
Route::post('addServiceEstimate', 'EstimateController@addServiceEstimate');

/* Winch Company APIs  */
Route::post('addWinchDriver', 'WinchCompanyController@addWinchDriver');
Route::post('updateWinchDriverData', 'WinchCompanyController@updateWinchDriverData');
Route::post('getOrders', 'WinchCompanyController@getOrders');
Route::post('assignWinchDriver', 'WinchCompanyController@assignWinchDriver');
Route::post('cancelAssignWinchDriver', 'WinchCompanyController@cancelAssignWinchDriver');
Route::post('getFreeDrivers', 'WinchCompanyController@getFreeDrivers');

/* Winch Driver APIs  */
Route::post('changeAvaliability', 'winchDriverController@changeAvaliability');
Route::post('acceptorder', 'winchDriverController@acceptorder');
Route::post('rejectorder', 'winchDriverController@rejectorder');

/* Offers APIs  */
Route::post('getLastestOffers', 'OffersController@getLastestOffers');
Route::post('getSpecificOffer', 'OffersController@getSpecificOffer');
Route::post('roleOffers', 'OffersController@roleOffers');
Route::post('addOffer', 'OffersController@addOffer');
Route::post('editOffer', 'OffersController@editOffer');
Route::post('deleteoffer', 'OffersController@deleteoffer');

/* Order APIs  */
Route::post('WinchDriverOrders', 'OrderController@WinchDriverOrders');
Route::post('CustomerOrders', 'OrderController@CustomerOrders');
Route::post('NearestTenWinchDrivers', 'OrderController@NearestTenWinchDrivers');
Route::post('makeWinchOrder', 'OrderController@makeWinchOrder');
Route::post('startTrip', 'OrderController@startTrip');
Route::post('finishTrip', 'OrderController@finishTrip');
Route::post('userCancelTrip', 'OrderController@userCancelTrip');
Route::post('addTripFeedBack', 'OrderController@addTripFeedBack');

/* Parking APIs  */
Route::post('getIndividualCarHistoryLocations', 'ParkingController@getIndividualCarHistoryLocations');
Route::post('getLastestCarLocation', 'ParkingController@getLastestCarLocation');


/*firebase message*/
Route::post('/SendMessage','FirebaseController@index');
/*Chatbot message*/
Route::post('/Chatbot','ChatbotController@search');
/*Driver Location Routes*/
Route::post('/storedriver','FirebaseController@storedriver');
Route::post('/updatedriver','FirebaseController@updatedriver');
Route::post('/deletedriver','FirebaseController@deletedriver');
Route::post('/viewdriver','FirebaseController@viewdriver');
Route::post('/getmessage','FirebaseController@getmessage');
