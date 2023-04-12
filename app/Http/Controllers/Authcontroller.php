<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Authcontroller extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());
        if(!Auth::attempt(['email' => $request->email,'password'=>$request->password])){
            return $this->error('',$request, 401);
        }
        $user= User::where('email', $request->email)->first();
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API of ' . $user->email)->plainTextToken
        ]);
    }

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());
        $user = User::create([
            'phoneNumber' => $request->phoneNumber,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API of ' . $user->email)->plainTextToken
        ]);
    }

    public function logout()
    {
        return response()->json('This is my LOGOUT method');
    }
}
