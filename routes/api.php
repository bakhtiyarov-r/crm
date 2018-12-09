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
	
	Route::get('users/{user}', 'UserController@userShow');
	Route::post('user/avatar', 'UsersProfileController@updateAvatar');

	// get /projects - index
	// get /projects/create - create
	// post /projects - store
	// get /projects/{project}/edit - edit 
	// put /projects/{project} - update 
	// delete /projects/{project} - destroy

	Route::resource('projects', ProjectController::class);

	Route::post('{company}/projects', 'ProjectController@store');
	Route::get('projects/{project}/tasks', 'ProjectController@showTasks');

	Route::post('projects/{project}/tasks', 'TaskController@store');
	
	Route::resource('tasks', TaskController::class);

	Route::post('{task}/document', 'DocumentController@store');
	Route::put('{task}/document', 'DocumentController@destroy');


});

Route::group(['middleware' => 'jwt.refresh'], function() {
	Route::get('auth/refresh', 'AuthController@refresh');
});




