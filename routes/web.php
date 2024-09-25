<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaptchaController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KaryawanMcuController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\MedicalConditionController;
use App\Http\Controllers\StatusFittoworkController;
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
        Route::post('/import-karyawan', [KaryawanController::class, 'importKaryawan']);
        Route::resource('/karyawan', KaryawanController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    });
    Route::group(['prefix' => 'mcu-karyawan','middleware' => 'checklogin'], function () {
        Route::get('/', function () {
            return view('data-mcu.index');
        });
        Route::get('/export-medical', [KaryawanMcuController::class, 'exportExcel']);
        Route::get('/show-medical', [KaryawanMcuController::class, 'getDataMcu']);
        Route::resource('/karyawan-mcu', KaryawanMcuController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    });

    Route::group(['prefix' => 'managemen-user','middleware' => 'checklogin'], function () {
        Route::get('/', function () {
            return view('pengguna.index');
        });
        Route::resource('/users', UsersController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    });
    Route::group(['prefix' => 'group-user','middleware' => 'checklogin'], function () {
        Route::get('/', function () {
            return view('errors.comming_soon');
        });
        Route::get('permissions', [RolesController::class, 'getPermissions']);
        Route::resource('/roles', RolesController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    });
    Route::group(['prefix' => 'setting-app','middleware' => 'checklogin'], function () {
        Route::get('/', function () {
            return view('errors.comming_soon');
        });
    });

    Route::resource('/medical-condition',  MedicalConditionController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    Route::resource('/fitwork-condition',  StatusFittoworkController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    Route::get('/download-karyawan-template', [KaryawanController::class, 'downloadTemplate'])->name('karyawan.downloadTemplate');

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
Route::post('/captcha/validate', [CaptchaController::class, 'validateTextCaptcha']);

Route::get('/captcha/text-image', [CaptchaController::class, 'showTextCaptchaImage']);
// //log-viewers
// Route::get('log-viewers', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);


// Route::get('/send-test-email', function () {
//     Mail::raw('This is a test email from Laravel.', function ($message) {
//         $message->to('mahdiawan.nk@gmail.com')
//                 ->subject('Test Email');
//     });

//     return 'Test email sent!';
// });
