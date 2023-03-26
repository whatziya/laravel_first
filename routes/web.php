<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('verify-mail/{token}',[UserController::class,'verificationMail']);

Route::get('/students-store', function () {
    return view('students.store');
});

Route::get('/get-students', function () {
    return view('students.students');
});

Route::get('get-all-students',[StudentController::class,'getData'])->name('getData');

Route::get('editUser/{id}',[StudentController::class,'getStudentData']);

Route::post('update-data',[StudentController::class,'updateStudent'])->name("updateStudent");

Route::get('delete-data/{id}',[StudentController::class,'deleteData']);