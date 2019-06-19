<?php

use Illuminate\Http\Request;
// use Helper;
// use Curl;
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
//

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('team','TeamController@list')->name('team-api');
Route::post('category','CategoryController@list')->name('category-api');
Route::post('tag','TagController@list')->name('tag-api');
Route::post('sosmed','SosmedController@list')->name('sosmed-api');
Route::post('product','ProductController@list')->name('product-api');