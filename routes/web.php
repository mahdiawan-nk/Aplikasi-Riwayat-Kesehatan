<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaptchaController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KaryawanMcuController;


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


Route::group(['prefix' => 'auth-admin'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'show']);
    Route::get('/verifikasi-otp', function () {
        return view('check-otp');
    });
});

Route::group(['prefix' => 'auth-user'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/verifikasi-otp', function () {
        return view('check-otp');
    });
    Route::get('/me', [AuthController::class, 'show']);
});

Route::group(['prefix' => 'panel-admin'], function () {
    Route::get('/', function () {
        return view('authentication-operator');
    });
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    Route::group(['prefix' => 'master-karyawan','middleware' => 'checklogin'], function () {
        Route::get('/', function () {
            return view('karyawan.index');
        });
        Route::resource('/karyawan', KaryawanController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    });
    Route::group(['prefix' => 'mcu-karyawan','middleware' => 'checklogin'], function () {
        Route::get('/', function () {
            return view('data-mcu.index');
        });
        Route::resource('/karyawan-mcu', KaryawanMcuController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    });
});

Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::get('/resend-otp', [AuthController::class, 'reSendOTP']);
Route::get('/mcu-user/{id}', [KaryawanMcuController::class, 'showByKaryawan']);

Route::get('/', function () {
    return view('authentication-user');
});

Route::get('/mcu-user', function () {
    return view('mcu-user.index');
});

// Route::get('/login', function () {
//     return view('authentication-operator');
// });

// Route::post('/login', [AuthController::class, 'login']);

// Route::get('/verifikasi-otp', function () {
//     return view('check-otp');
// });


// Route::get('/dashboard', function () {
//     return view('dashboard');
// });



// Route::get('/master-karyawan', function () {
//     return view('karyawan.index');
// });

// Route::get('/mcu-karyawan', function () {
//     return view('data-mcu.index');
// });

// Route::get('/captcha/text', [CaptchaController::class, 'showTextCaptcha']);
// Route::post('/captcha/validate', [CaptchaController::class, 'validateTextCaptcha']);

// Route::get('/captcha/text-image', [CaptchaController::class, 'showTextCaptchaImage']);
// //log-viewers
// Route::get('log-viewers', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);


// Route::get('/send-test-email', function () {
//     Mail::raw('This is a test email from Laravel.', function ($message) {
//         $message->to('mahdiawan.nk@gmail.com')
//                 ->subject('Test Email');
//     });

//     return 'Test email sent!';
// });
