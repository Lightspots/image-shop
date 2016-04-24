<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'PublicController@index');

Route::get('/a', 'AdmController@index');

//AUTH
Route::group(['prefix' => 'api'], function(){
    Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
});

//ADM API
Route::group(['prefix' => 'api'], function(){
    Route::resource('sizes', 'SizeController');
    Route::resource('albums', 'AlbumController');
    Route::post('albums/process/{id}', 'AlbumController@processImagesOfAlbum');
    Route::get('orders', 'OrderController@index');
    Route::get('orders/{id}', 'OrderController@show');
    Route::delete('orders/{id}', 'OrderController@destroy');
});

//PUBLIC API
Route::group(['prefix' => 'api'], function(){
    Route::get('publicalbums', 'PublicController@publicAlbums');
    Route::get('publicalbums/{id}', 'PublicController@show');
    Route::post('orders', 'PublicController@order');
    Route::get('mail', 'PublicController@mail');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
