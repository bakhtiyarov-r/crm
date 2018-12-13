<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\Company;
use App\User;
use App\UsersProfile;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\ViewUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\StoreUserRequest;



class UserController extends Controller
{

	public function index()
    {
        $users = Auth::user()->company->users->load('profile');
        return response([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function show(ViewUserRequest $request, User $user)
    {
        $user = $user->load('profile');
        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function store(StoreUserRequest $request)
    { 
        $user = new User($request->all());
        Auth::user()->company->users()->save($user);
        $user->profile->fill($request->all())->save();

        return response([
            'status' => 'success',
            'data' => $user
           ], 200);
    }

    public function update(UpdateUserRequest $request, User $user)
	{
        $user->fill($request->all())->save();
	    $user->profile->fill($request->all())->save();
	    return response([
	        'status' => 'success',
	        'data' => $user
	       ], 200);
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteUserRequest $request, User $user)
    {
        $user->delete();
        return response([
            'status' => 'success',
            'redirect' => '/company'
        ]);
    }
}
