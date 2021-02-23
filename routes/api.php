<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LeadController;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\OrganisationController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('users', UserController::class)->middleware('auth:api');
Route::apiResource('leads', LeadController::class)->middleware('auth:api');
Route::apiResource('accounts', AccountController::class)->middleware('auth:api');
Route::apiResource('organisations', OrganisationController::class)->middleware('auth:api');
