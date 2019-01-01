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

	// get /projects - index
	// get /projects/create - create
	// post /projects - store
	// get /projects/{project}/edit - edit 
	// put /projects/{project} - update 
	// delete /projects/{project} - destroy
	
	
	Route::resource('users', UserController::class);
	

	Route::put('profile/edit', 'UsersProfileController@update');
	Route::post('profile/avatar', 'UsersProfileController@updateAvatar');

	
	Route::resource('projects', ProjectController::class);
	Route::post('{company}/projects', 'ProjectController@store');

	
	Route::resource('tasks', TaskController::class);
	Route::post('{project}/tasks', 'TaskController@store');
	Route::put('tasks/{task}/close', 'TaskController@closeTask');
	Route::put('tasks/{task}/done', 'TaskController@doneTask');


	Route::post('{task}/document', 'DocumentController@store');
	Route::put('{task}/document', 'DocumentController@destroy');

});

Route::group(['middleware' => 'jwt.refresh'], function() {
	Route::get('auth/refresh', 'AuthController@refresh');
});




