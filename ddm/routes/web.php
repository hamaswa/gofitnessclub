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

Route::get('/', function(){
    return view("index");
})->name("root");
Route::get('/home', "DailyDiteController@home")->name("home");
// Route::get('/library', "DailyDiteController@library")->name("library");
// Route::get('/show_monthly_dite', "DailyDiteController@monthly_dite");
// // Route::get('/show_monthly_dite', "DailyDiteController@monthly_dite")->name("monthly_dite");
// // Route::get('/show_monthly_dite_report', "DailyDiteController@monthly_dite_report");

// Route::get("dailydite/upload_image","DailyDiteController@imageform");
// Route::get("dailydite/dite_defaults/{id}","DailyDiteController@dite_defaults");

