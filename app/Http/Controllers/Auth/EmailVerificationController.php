<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function verifyEmail(Request $request)
    {
         $user = User::find($request->route('id'));
        
        if ($user->hasVerifiedEmail()) {
      
            return response()->json(['verifyMessage'=>"has already been verified"],200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

       return response()->json(['verifyMessage'=>"verification successful"],200);
    }
}
