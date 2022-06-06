<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|min:6'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        event(new Registered($user));

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['access_token' => $accessToken],201);
          
       }  
    
     public function home(Request $request){
            return response()->setStatusCode(200);
     }


    public function login(Request $request)
    {     
   
        $credentials = $request->validate([         
            'email' => 'email|required',
            'password' => 'required|min:6'
        ]);    
      
        //  Auth::logout();
        if (Auth::attempt($credentials)) {  

                 $request->session()->regenerate();
              
                 return response()->json(['message'=>'Login successfully'],200);
        }

       return response()->json([
                   'errors' => ['The provided credentials do not match our records.'],
              ],400);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(
            [
                'message' => 'Logged out'
            ]
        );

    }
}
