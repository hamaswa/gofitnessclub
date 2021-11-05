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


Route::get("/settings/delete-shop/{id}","SettingsController@delete_shop")->name("delete_shop");
Route::get("/settings/shop-list","SettingsController@get_shop_list")->name("get_shop_list");
Route::post("/settings/shop-list","SettingsController@save_shop_list")->name("post_shop_list");

Route::get("/settings/delete-brand/{id}","SettingsController@delete_brand")->name("delete_brand");
Route::get("/settings/brand-list","SettingsController@get_brand_list")->name("get_brand_list");
Route::post("/settings/brand-list","SettingsController@save_brand_list")->name("post_brand_list");
Route::get("buying/show-monthly-buying","BuyingController@show_monthly_buying")->name("show_monthly_buying");


Route::get('/buying', "BuyingController@index")->name("buying-index");
Route::post("/buying/buy_meal","BuyingController@buy_meal")->name("buy_meal");
Route::post("/buying/buying-detail/{id}","BuyingController@buying_detail")->name("buying_detail");
Route::get("/buying/edit-item-bought","BuyingController@edit_item_bought")->name("edit_item_bought");
Route::post("/buying/update-item-bought","BuyingController@update_item_bought")->name("update_item_bought");
Route::get("/buying/delete-item-bought/{id}","BuyingController@delete_item_bought")->name("delete_item_bought");

Route::get('/home', "DailyDiteController@home")->name("home");
Route::get('/library', "DailyDiteController@library")->name("library");
Route::get("dailydite/add_dite","DailyDiteController@add_dite")->name("add_dite");
Route::get("dailydite/delete_dite","DailyDiteController@delete_dite")->name("delete_dite");
Route::get("dailydite/edit_dite","DailyDiteController@edit_dite")->name("edit_dite");
Route::post("dailydite/update_dite","DailyDiteController@update_dite")->name("update_dite");
Route::get("dailydite/edit","DailyDiteController@edit");
Route::post("dailydite/add_dite","DailyDiteController@add_dite");
Route::post("dailydite/dite_defaults","DailyDiteController@add_dite_defaults")->name("dite_defaults");
Route::post("dailydite/delete_food_item","DailyDiteController@delete_food_item")->name("delete_food_item");



Route::post("dailydite/user_weight_report","DailyDiteController@user_weight_report")->name("user_weight_report");

Route::post("dailydite/food_item_report","DailyDiteController@food_item_report")->name("food_item_report");

// Common Routes
Route::post("upload_image/{folder}","CommonController@upload_image")->name("upload_image");
Route::get("reports/monthly_dite","CommonController@monthly_dite")->name("monthly_dite");
Route::get("reports/monthly_dite_report","CommonController@monthly_dite_report")->name("monthly_dite_report");

