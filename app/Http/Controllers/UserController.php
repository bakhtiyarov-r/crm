<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\Company;
use App\User;
use App\UsersProfile;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Http\Request;


class UserController extends Controller
{

	public function index()
    {
        $users = User::with('profile')
        ->where('company_id', Auth::user()->company_id)
        ->get();
        return response([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function store(RegisterFormRequest $request)
    { 
        $user = new User;
        $user->company_id = Auth::user()->company_id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $usersProfile = new UsersProfile;
        $usersProfile->surname = $request->surname;
	    $usersProfile->phone = $request->phone;
	    $usersProfile->birthday = $request->birthday;
	    $usersProfile->position = $request->position;

        $user->profile()
        ->save($usersProfile);

        return response([
            'status' => 'success',
            'data' => $user
           ], 200);
    }

    public function update(Request $request)
	{
	    $usersProfile = Auth::user()->profile;
	    $usersProfile->surname = $request->surname;
	    $usersProfile->phone = $request->phone;
	    $usersProfile->birthday = $request->birthday;
	    $usersProfile->position = $request->position;

	    $user = Auth::user();
	    $user->email = $request->email;
	    $user->name = $request->name;

	    $user->profile()->save($usersProfile);
	    $user->save();
	    return response([
	        'status' => 'success',
	        'data' => $user
	       ], 200);
	}
}
