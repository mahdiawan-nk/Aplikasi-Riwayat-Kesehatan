<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KaryawanMcuController;
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
// Route::post('register', [AuthController::class, 'register']);
// Route::post('login', [AuthController::class, 'login']);
// // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
// //     return $request->user();
// // });

// Route::middleware(['auth:sanctum', 'check.token.expiration'])->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     // Route::resource('/karyawan', KaryawanController::class, ['except' => ['create', 'edit'],'as'=>'master']);
//     // Route::resource('/karyawan-mcu', KaryawanMcuController::class, ['except' => ['create', 'edit'],'as'=>'master']);
// });
// Route::post('/logout', [AuthController::class, 'logout']);
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Route::middleware('auth:sanctum')->group( function () {
//     Route::resource('/user', AuthController::class);
// });
