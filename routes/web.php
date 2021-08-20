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

use App\Models\User;

use App\Http\Controllers\FieldOfficerController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ImportExcelController;
use App\Http\Controllers\ExportExcelController;
use App\Http\Controllers\ReliefRecipientController;

Route::get('/', function () {
    $count = User::count();
    if ($count == 0) {
        return view('auth.register');
    } else {
        return view('auth.login');
    }
});
Route::auth('/register', function () {
    $count = User::count();

    return view('auth.register')->with('count', $count);
});

Auth::routes();


Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/map/get_evac', 'MapController@get_evac')->name('map.evacuation-centers');
    Route::resource('/map', 'MapController');
    Route::prefix('evacuation-centers')->group(function () {
        Route::get('/',         'EvacuationCenterController@index')->name('evacuation-center.index');
        Route::get('/create',   'EvacuationCenterController@create')->name('evacuation-center.create');
        Route::post('/store',   'EvacuationCenterController@store')->name('evacuation-center.store');
        Route::get('/edit',     'EvacuationCenterController@edit')->name('evacuation-center.edit');
        Route::post('/update',  'EvacuationCenterController@update')->name('evacuation-center.update');
        Route::get('/delete',   'EvacuationCenterController@delete')->name('evacuation-center.delete');
    });
});

Route::resource('/field_officers', 'FieldOfficerController');

Route::resource('/profile', 'ProfileController');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/chat', 'ChatController@index')->name('chat');
    Route::get('/chat/{id}', 'ChatController@getMessage');
    Route::post('chat', 'ChatController@sendMessage');
    Route::get('/chat/search', 'ChatController@search');

});

Route::resource('/field_officers', 'FieldOfficerController');
Route::resource('/profile', 'ProfileController');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/chat', 'ChatController@index')->name('chat');
    Route::get('/chat/{id}', 'ChatController@getMessage');
    Route::post('chat', 'ChatController@sendMessage');
    Route::get('search', 'ChatController@search');
    Route::get('/chat/all-users', 'ChatController@getAllUsers');
});
Route::resource('supplies', 'SupplyController');
Route::resource('inventory', 'InventoryController');

Route::post('/import_excel_supplies', 'ImportExcelController@import');
Route::get('/export_excel_supplies', 'ExportExcelController@export');

Route::resource('relief-recipient', 'ReliefRecipientController');
