<?php


namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\User;
use App\Company;
use App\UsersProfile;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function register(RegisterFormRequest $request)
	{
        $company = new Company;
        $company->title = $request->title;
        $company->email = $request->email;
        $company->save();
	    
        $user = new User;
	    $user->email = $request->email;
	    $user->name = $request->name;
	    $user->password = bcrypt($request->password);
	    $company->users()->save($user);


	    return response([
	        'status' => 'success',
	        'data' => $user
	       ], 200);
	}

	public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ( ! $token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }
        return response([
            'status' => 'success'
        ])
        ->header('Authorization', $token);
    }

    public function user(Request $request)
    {
        $user = User::with('profile')->find(Auth::user()->id);
        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

    public function logout()
	{
	    JWTAuth::invalidate();
	    return response([
	            'status' => 'success',
	            'msg' => 'Logged out Successfully.'
	        ], 200);
	}
}
