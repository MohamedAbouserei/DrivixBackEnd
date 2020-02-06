<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
//////// Dashboard
Route::get('/Admin','HomeController@index');
///
/// Manage Gas Station
Route::get('/gasStation' , 'Gasstation_controller@GasStationCms')->name('gasStation');
Route::get('/AddGasStation' , 'Gasstation_controller@AddGasStation');
Route::post('/saveGasStation' , 'Gasstation_controller@storeGasStation');
Route::get('/editGasStation/{id}' , 'Gasstation_controller@EditGasStationCms')->name('editGasStation');
Route::post('/updateGasStation' , 'Gasstation_controller@updateGasStationCms');
Route::get('/getGasStationAjax' , 'Gasstation_controller@getGasStationAjax')->name('getGasStationAjax');
Route::post('/deleteGasStation' , 'Gasstation_controller@deleteGasStation');
/// Manage Products
Route::get('/manage-products' , 'productController@ProductsCms')->name('products');
Route::get('/get-products' , 'productController@getProductAjax')->name('getProductAjax');
Route::get('/get-products/{id}' , 'productController@getProductCms');
Route::post('/lock-unlock-product' , 'productController@lockAunlockProduct')->name('lock-unlock-product');

// Mange Roles
Route::get('/manage-roles' , 'RoleController@RolesCms');
Route::get('/get-roles' , 'RoleController@getRolesAjax')->name('getRolesAjax');
Route::post('/lock-unlock-roles' , 'RoleController@lockAunlockRoles')->name('lock-unlock-roles');
Route::get('/get-Role/{id}' , 'RoleController@getRoleCms');

// site Setting
Route::get('/site-setting' , 'SiteSetting@index');
Route::post('/site-setting-update' , 'SiteSetting@update');
Route::post('/site-setting-update-logo' , 'SiteSetting@updateLogo');

/// Manage Supervisors
Route::get('/manage-supervisors' , 'SupervisorController@SupervisorsCms')->name('supervisors');
Route::get('/get-supervisors' , 'SupervisorController@getSupervisorsCms')->name('getSupervisors');
Route::get('/get-supervisor/{id}' , 'SupervisorController@getSupervisorCms');
Route::post('/lock-unlock-supervisor' , 'SupervisorController@lockAunlock')->name('lock-unlock-supervisor');
Route::post('/updateSupervisor' , 'SupervisorController@updateSupervisorCms');
Route::get('/AddSupervisor' , 'SupervisorController@AddSupervisor');
Route::post('/saveSupervisor' , 'SupervisorController@storeSupervisor');

// manage mails
Route::get('/manage-mails' , 'EmailController@mymailsCms')->name('mymailsCms');
Route::get('/get-my-mails' , 'EmailController@mymailsCmsAjax')->name('getMails');
Route::get('/getMail/{id}' , 'EmailController@getMailCmsAjax')->name('getMail');
Route::post('/changeMailStatus/{id}' , 'EmailController@changeMailStatusCmsAjax');
Route::get('/AddMail' , 'EmailController@AddMailCms');
Route::post('/StoreMail' , 'EmailController@StoreMail');

// Statistics
Route::get('/stat' , 'Statistics@index');
Route::get('/getStatAjax' , 'Statistics@getStatAjax');

