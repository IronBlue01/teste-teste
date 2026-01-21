<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);


/**
 * ROTAS AUTENTICADAS
*/
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [AuthController::class,'userAuthenticated']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
