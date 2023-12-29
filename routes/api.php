<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;

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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => 'jwt.verify'], function(){
    Route::get('user', [UserController::class, 'show']);
    
    Route::get('member/{id}', [MemberController::class, 'show']);
    Route::post('member', [MemberController::class, 'store']);
    Route::put('member/{id}', [MemberController::class, 'update']);
});
