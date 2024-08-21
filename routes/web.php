<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaptchaController;
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
    return view('authentication-user');
});
Route::get('/panel-admin', function () {
    return view('authentication-operator');
});

Route::get('/login', function () {
    return view('authentication-operator');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/mcu-user', function () {
    return view('mcu-user.index');
});

Route::get('/master-karyawan', function () {
    return view('karyawan.index');
});

Route::get('/mcu-karyawan', function () {
    return view('data-mcu.index');
});

Route::get('/captcha/text', [CaptchaController::class, 'showTextCaptcha']);
Route::post('/captcha/validate', [CaptchaController::class, 'validateTextCaptcha']);

Route::get('/captcha/text-image', [CaptchaController::class, 'showTextCaptchaImage']);
//log-viewers
Route::get('log-viewers', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
