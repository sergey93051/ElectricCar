<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Auth\EmailVerificationController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth::routes(['verify' => true]);

Route::post('/email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum', 'throttle:6,1');
// Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('signed', 'throttle:6,1');
Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json('message', 'Verification link sent!');
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::any('login', [AuthController::class, 'login'])->name('login')->middleware('checkVerified');
Route::post('register', [AuthController::class, 'signUp']);

// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

Route::group(['prefix' => '/', 'middleware' => 'auth:sanctum'], function () {   

    Route::get('/home', [AuthController::class, 'home'])->name('home');

});

 Route::get('profile', function () {
    return response(201)->json(User::get());
});