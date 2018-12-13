<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Image;

class UsersProfileController extends Controller
{
    public function updateAvatar(Request $request){

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500',
        ]);

        $avatarName = Auth::user()->id . '_avatar' . '.' . $request->file('avatar')->getClientOriginalExtension();

        $image = Image::make($request->file('avatar'))->fit(300, 300);
        
        $storage = storage_path().'/app/public/avatars/';
        $path = $image->save(
            $storage.$avatarName
        );
 
        Auth::user()->profile->avatar = $avatarName;
        Auth::user()->profile->save();

        return response([
            'status' => 'success',
            'data' => $avatarName
        ]);

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
