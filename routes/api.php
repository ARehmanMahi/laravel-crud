<?php

use App\Http\Controllers\MemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiAuthController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::apiResource('/members', MemberController::class);

 Route::middleware(['auth:sanctum', 'verified'])->group(function () {
     Route::apiResource('/members', MemberController::class);
 });


Route::post("/registers",[ApiAuthController::class,'register']);
Route::post("/logins",[ApiAuthController::class,'login']);
Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post("/logouts",[ApiAuthController::class,'logout']);
});
