<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post("forget-password",[UserController::class,"forgetPassword"]);

Route::group(['middleware'=>'api'],function($routes){

    Route::post('register', [UserController::class,'register']);
    Route::post('login', [UserController::class,'login']);
    Route::get('logout', [UserController::class,'logout']);
    Route::get('profile', [UserController::class,'profile']);
    Route::post('profile-update', [UserController::class,'updateProfile']);
    Route::get("send-verify-mail/{email}", [UserController::class, "sendVerifyMail"]);
    Route::get("refresh-token",[UserController::class, "refreshToken"]);
    Route::post('students-store', [StudentController::class,'store']);
    //Route::get('/students', [StudentController::class,'getData']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
