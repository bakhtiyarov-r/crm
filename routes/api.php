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

Route::post('auth/register', 'AuthController@register');

Route::post('auth/login', 'AuthController@login');



Route::group(['middleware' => 'jwt.auth'], function() {
	Route::get('auth/user', 'AuthController@user');
	Route::post('auth/logout', 'AuthController@logout');

	Route::post('user/edit', 'UserController@update');
	Route::get('users/show', 'UserController@index');
	Route::post('user/create', 'UserController@store');

	Route::post('project/create', 'ProjectController@store');
	Route::get('project/show', 'ProjectController@index');
	Route::get('project/item-{id}', 'ProjectController@show');
	Route::post('project/edit-item-{id}', 'ProjectController@update');
	Route::delete('project/item-{id}', 'ProjectController@destroy');

	Route::get('project/item-{id}/tasks', 'ProjectController@showTasks');
	Route::post('project/item-{id}/tasks/create', 'TaskController@store');

	Route::get('task/show', 'TaskController@index');
	Route::get('task/item-{id}', 'TaskController@show');
	Route::post('task/edit-item-{id}', 'TaskController@update');
	Route::delete('task/item-{id}', 'TaskController@destroy');
	Route::post('task/create', 'TaskController@store');

});

Route::group(['middleware' => 'jwt.refresh'], function() {
	Route::get('auth/refresh', 'AuthController@refresh');
});




