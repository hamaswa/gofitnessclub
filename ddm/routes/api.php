<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/buying', "BuyingController@index")->name("buying-index");
Route::post("/buying/buy_meal","BuyingController@buy_meal")->name("buy_meal");
Route::post("/buying/buying-detail","BuyingController@buying_detail")->name("buying_detail");

Route::get("dailydite/add_dite","DailyDiteController@add_dite")->name("add_dite");
Route::get("dailydite/delete_dite","DailyDiteController@delete_dite")->name("delete_dite");
Route::get("dailydite/edit_dite","DailyDiteController@edit_dite")->name("edit_dite");
Route::post("dailydite/update_dite","DailyDiteController@update_dite")->name("update_dite");
Route::get("dailydite/edit","DailyDiteController@edit");
Route::post("dailydite/add_dite","DailyDiteController@add_dite");
Route::post("dailydite/upload_image","CommonController@upload_image")->name("upload_image");
Route::post("dailydite/monthly_dite","DailyDiteController@show_monthly_dite");
Route::post("dailydite/dite_defaults","DailyDiteController@add_dite_defaults")->name("dite_defaults");
Route::post("dailydite/delete_food_item","DailyDiteController@delete_food_item")->name("delete_food_item");

Route::get("dailydite/monthly_dite","DailyDiteController@show_monthly_dite");
Route::get("dailydite/monthly_dite_report","DailyDiteController@show_monthly_dite_report");
Route::post("dailydite/user_weight_report","DailyDiteController@user_weight_report")->name("user_weight_report");

Route::post("dailydite/monthly_dite_report/food_item_report","DailyDiteController@food_item_report")->name("food_item_report");
