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
    Route::group(['prefix' => 'master-karyawan', 'middleware' => 'checklogin'], function () {
        Route::get('/', function () {
            return view('karyawan.index');
        });
        Route::post('/import-karyawan', [KaryawanController::class, 'importKaryawan']);
        Route::resource('/karyawan', KaryawanController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    });
    Route::group(['prefix' => 'mcu-karyawan', 'middleware' => 'checklogin'], function () {
        Route::get('/', function () {
            return view('data-mcu.index');
        });
        Route::get('/export-medical', [KaryawanMcuController::class, 'exportExcel']);
        Route::get('/show-medical', [KaryawanMcuController::class, 'getDataMcu']);
        Route::resource('/karyawan-mcu', KaryawanMcuController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    });

    Route::group(['prefix' => 'managemen-user', 'middleware' => 'checklogin'], function () {
        Route::get('/', function () {
            return view('pengguna.index');
        });
        Route::resource('/users', UsersController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    });
    Route::group(['prefix' => 'group-user', 'middleware' => 'checklogin'], function () {
        Route::get('/', function () {
            return view('errors.comming_soon');
        });
        Route::get('permissions', [RolesController::class, 'getPermissions']);
        Route::resource('/roles', RolesController::class, ['except' => ['create', 'edit'], 'as' => 'master']);
    });
    Route::group(['prefix' => 'setting-app', 'middleware' => 'checklogin'], function () {
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


Route::get('/captcha/math', [CaptchaController::class, 'showMathCaptcha']);
Route::post('/captcha/validate', [CaptchaController::class, 'validateTextCaptcha']);
Route::post('/captcha/math/validate', [CaptchaController::class, 'validateMathCaptcha']);

Route::get('/captcha/text-image', [CaptchaController::class, 'showTextCaptchaImage']);


Route::get('ldap', function () {
    $ldap_password = 'password';
    $ldap_username = 'cn=read-only-admin,dc=example,dc=com';
    $ldap_connection = ldap_connect("ldap.forumsys.com");

    if (FALSE === $ldap_connection) {
        // Uh-oh, something is wrong...
        echo 'Unable to connect to the ldap server';
    }

    ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
    ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.

    if (TRUE === ldap_bind($ldap_connection, $ldap_username, $ldap_password)) {
        echo "okeee";
    } else {
        echo "tidak oke";
    }

    $search_base = "dc=example,dc=com"; // Ganti dengan base DN yang sesuai
    $search_filter = "(uid=*)"; // Ganti dengan filter pencarian yang diinginkan
    $search_attributes = ["cn", "sn", "mail"]; // Atribut yang ingin diambil

    // Melakukan pencarian
    $ldap_search = ldap_search($ldap_connection, $search_base, $search_filter, $search_attributes);

    if ($ldap_search) {
        $result_entries = ldap_get_entries($ldap_connection, $ldap_search);
        echo "<pre>";
        print_r($result_entries); // Menampilkan hasil pencarian
        echo "</pre>";
    } else {
        echo "Pencarian gagal: " . ldap_error($ldap_connection);
    }
});

Route::get('ldap/users', function () {
    // $ldap_username = 'cn=read-only-admin,dc=example,dc=com';

    $email = 'galieleo@ldap.forumsys.com'; // Ganti dengan email yang dimasukkan pengguna
    $password = 'password'; // Ganti dengan password yang dimasukkan pengguna

    // Mengonversi email ke format DN, jika diperlukan
    $ldap_username = "uid=" . explode('@', $email)[0] . ",dc=example,dc=com"; // Sesuaikan dengan DN yang benar

    $ldap_connection = ldap_connect("ldap.forumsys.com");

    if (FALSE === $ldap_connection) {
        echo 'Unable to connect to the LDAP server';
        exit;
    }

    ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0);

    if (TRUE === ldap_bind($ldap_connection, $ldap_username, $password)) {
        echo "Login berhasil!";
        // Melakukan pencarian detail pengguna
        $search_base = "dc=example,dc=com"; // Sesuaikan dengan base DN
        $search_filter = "(uid=" . explode('@', $email)[0] . ")"; // Filter pencarian

        $ldap_search = ldap_search($ldap_connection, $search_base, $search_filter);

        if ($ldap_search) {
            $result_entries = ldap_get_entries($ldap_connection, $ldap_search);
            if ($result_entries['count'] > 0) {
                // Menampilkan detail pengguna
                echo "<h3>Detail Pengguna:</h3>";
                echo "Nama: " . $result_entries[0]['cn'][0] . "<br>";
                echo "Nama Belakang: " . $result_entries[0]['sn'][0] . "<br>";
                echo "Email: " . $result_entries[0]['mail'][0] . "<br>";
            } else {
                echo "Pengguna tidak ditemukan.";
            }
        } else {
            echo "Pencarian gagal: " . ldap_error($ldap_connection);
        }
    } else {
        echo "Login gagal: " . ldap_error($ldap_connection);
    }
});
