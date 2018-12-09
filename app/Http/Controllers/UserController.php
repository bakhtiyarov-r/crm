<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\Company;
use App\User;
use App\UsersProfile;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\UserShowRequest;
use Illuminate\Http\Request;


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

    public function userShow(UserShowRequest $request, User $user)
    {
        $user = $user->load('profile');
        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function store(RegisterFormRequest $request)
    { 
        $user = new User($request->all());
        Auth::user()->company->users()->save($user);
        $user->profile->fill($request->all())->save();

        return response([
            'status' => 'success',
            'data' => $user
           ], 200);
    }

    public function update(Request $request)
	{
        $user = Auth::user()->fill($request->all())->save();
	    Auth::user()->profile->fill($request->all())->save();
	    return response([
	        'status' => 'success',
	        'data' => $user
	       ], 200);
	}
}
