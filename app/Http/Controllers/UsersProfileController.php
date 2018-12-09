<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class UsersProfileController extends Controller
{
    public function updateAvatar(Request $request){

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500',
        ]);

        $avatarName = Auth::user()->id . '_avatar' . '.' . $request->file('avatar')->getClientOriginalExtension();
        
		$path = $request->file('avatar')->storeAs(
            'avatars', $avatarName
        );
 
        Auth::user()->profile->avatar = $avatarName;
        Auth::user()->profile->save();

        return response([
            'status' => 'success',
            'data' => $avatarName
        ]);

    }
}
