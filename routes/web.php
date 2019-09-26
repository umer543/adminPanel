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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/index', function(){
    return view('adminPanel.index');
});

Route::get('/tables', function(){
    return view('adminPanel.tables');
})->name('tables');

Route::get('/forms', function(){
    return view('adminPanel.forms');
})->name('forms');

Route::get('/charts', function(){
    return view('adminPanel.charts');
})->name('charts');

Route::get('/calender', function(){
    return view('adminPanel.calender');
})->name('calender');

Route::get('/maps', function(){
    return view('adminPanel.maps');
})->name('maps');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('users', 'UserController');
Route::post('assignRole', 'UserController@assignRole');
Route::post('deleteRole', 'UserController@deleteRole');
