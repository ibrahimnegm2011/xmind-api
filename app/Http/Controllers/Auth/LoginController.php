<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->input('username'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            $token = base64_encode(Str::random(40));
            User::where('username', $request->input('username'))->update(['api_token' => $token]);

            $response = [
                'token' => $token,
                'user' => $user
            ];

            return $this->success($response);
        } else {
            return $this->fail('invalid_credentials', "Username or password is not correct", [], 401);
        }

    }

    public function loadUser()
    {
        if (Auth::user()) {
            $response = [
                'token' => Auth::user()->api_token,
                'user' => Auth::user()
            ];

            return $this->success($response);
        } else {
            return $this->fail('invalid_token', "User Token has been expired", [], 401);
        }
    }
}
