<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataUser = User::all();
        $success['data'] = $dataUser;

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function show(Request $request)
    {

        if (session('role') == 'admin') {
            $user = Auth::user();
        } else {
            $user = Auth::guard('karyawan')->user();
        }


        if (!$user) {
            // Return an unauthorized response if no user is authenticated
            return response()->json(['message' => 'Unauthorized', 'data' => Session::all()], 200);
        }
        $success['data'] = $user;

        return $this->sendResponse($success, 'User data retrieved successfully.');
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    // public function login(Request $request)
    // {
    //     // Tentukan apakah login untuk admin atau user
    //     if ($this->isAdminLogin($request)) {
    //         // Logika login untuk Admin (User model)
    //         $credentials = $request->only('email', 'password');

    //         $user = User::where('email', $credentials['email'])->first();

    //         if ($user && Auth::attempt($credentials)) {
    //             $tokenResult = $user->createToken('MyApp', ['admin']);
    //             $token = $tokenResult->plainTextToken;

    //             // Atur waktu kedaluwarsa token
    //             $tokenResult->accessToken->expires_at = Carbon::now()->addHours(2);
    //             $tokenResult->accessToken->save();

    //             $success['token'] = $token;
    //             $success['name'] = $user->name;
    //             $success['role'] = 'admin';


    //             $this->SendOtp($user->email);

    //             return $this->sendResponse($success, 'Admin login successfully.');
    //         }
    //     } else {
    //         // Logika login untuk User (Karyawan model)
    //         $credentials = $request->only('no_badge', 'password');


    //         $user = Karyawan::where('no_badge', $credentials['no_badge'])
    //             ->first();

    //         if ($user && Auth::guard('karyawan')->attempt($credentials)) {
    //             $tokenResult = $user->createToken('MyApp', ['karyawan']);
    //             $token = $tokenResult->plainTextToken;

    //             // Atur waktu kedaluwarsa token
    //             $tokenResult->accessToken->expires_at = Carbon::now()->addHours(2);
    //             $tokenResult->accessToken->save();

    //             $success['token'] = $token;
    //             $success['name'] = $user->nama_karyawan;
    //             $success['role'] = 'karyawan';
    //             $this->SendOtp($user->email);


    //             return $this->sendResponse($success, 'User login successfully.');
    //         } else {
    //         }
    //     }
    //     return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);

    //     // Jika gagal login

    // }

    public function login(Request $request)
    {
        // Tentukan apakah login untuk admin atau karyawan
        if ($this->isAdminLogin($request)) {
            // Logika login untuk Admin (User model)
            $credentials = $request->only('email', 'password');

            $user = User::where('email', $credentials['email'])->first();

            if ($user && Hash::check($credentials['password'], $user->password)) {
                // Login sukses
                Auth::login($user);

                $success['name'] = $user->name;
                $success['role'] = 'admin';

                // Kirim OTP ke email admin
                $this->SendOtp($user->email);
                session($success);
                return $this->sendResponse($success, 'Admin login successfully.');
            }
        } else {
            // Logika login untuk Karyawan (Karyawan model)
            $credentials = $request->only('no_badge', 'password');

            $karyawan = Karyawan::where('no_badge', $credentials['no_badge'])->first();

            if ($karyawan && Hash::check($credentials['password'], $karyawan->password)) {
                // Login sukses untuk karyawan
                Auth::guard('karyawan')->login($karyawan);

                $success['name'] = $karyawan->nama_karyawan;
                $success['role'] = 'karyawan';

                // Kirim OTP ke email karyawan
                $this->SendOtp($karyawan->email);
                session($success);
                return $this->sendResponse($success, 'User login successfully.');
            }
        }

        // Jika gagal login
        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
    }

    private function SendOtp($email)
    {
        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        // Store OTP in Redis with a 5-minute expiration
        Redis::setex('otp_' . $email, 600, $otp);
        // Send OTP by email
        Mail::to($email)->send(new SendOtpMail($otp));
    }

    public function reSendOTP()
    {
        if (session('role') == 'admin') {
            $user = Auth::user();
        } else {
            $user = Auth::guard('karyawan')->user();
        }
        $this->SendOtp($user->email);
        return $this->sendResponse($user, 'OTP sent, check your email.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);
        if (session('role') == 'admin') {
            $user = Auth::user();
        } else {
            $user = Auth::guard('karyawan')->user();
        }
        $email = $user->email;
    
        $otpKey = "otp_{$email}";
        $storedOtp = Redis::get($otpKey);

        if (!$storedOtp) {
            return response()->json(['message' => 'OTP has expired. Please request a new one.'], 422);
        }

        if ($request->otp != $storedOtp) {
            return response()->json(['message' => 'Invalid OTP.'], 422);
        }

        // Delete the OTP from Redis after successful verification
        Redis::del($otpKey);

        return response()->json(['message' => 'OTP verified successfully.', 'roles' => session('role')], 200);
    }


    // Method untuk menentukan apakah login admin
    protected function isAdminLogin($request)
    {
        // Misalnya, menggunakan field hidden atau role untuk menentukan tipe login
        return $request->input('type') === 'admin';
    }
    public function logout(Request $request)
    {
        // Ambil user yang sedang login
        $roles = session('role');
        if ($roles == 'admin') {
            $user = Auth::user();
        } else {
            $user = Auth::guard('karyawan')->user();
        }

        if ($user) {
            // Hapus OTP setelah verifikasi
            Redis::del('otp_' . $user->email);

            // Cek peran user apakah admin atau karyawan
            if ($user instanceof \App\Models\User) {
                // Proses logout untuk admin
                $role = 'Admin';
                Auth::logout(); // Logout dari sesi
            } elseif ($user instanceof \App\Models\Karyawan) {
                // Proses logout untuk karyawan
                $role = 'Karyawan';
                Auth::guard('karyawan')->logout(); // Logout dari sesi
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Invalidate sesi saat ini dan regenerasi token CSRF
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['message' => $role . ' logged out successfully.']);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
    // public function logout(Request $request)
    // {
    //     // Ambil user yang sedang login
    //     $user = Auth::user();
    //     // Hapus OTP setelah verifikasi
    //     Redis::del('otp_' . $user->email);
    //     if ($user) {
    //         // Cek peran user apakah admin atau karyawan
    //         if ($user instanceof User) {
    //             // Proses logout untuk admin
    //             $role = 'Admin';
    //         } elseif ($user instanceof Karyawan) {
    //             // Proses logout untuk karyawan
    //             $role = 'Karyawan';
    //         } else {
    //             return response()->json(['message' => 'Unauthorized'], 401);
    //         }

    //         // Cek apakah token ID ada dalam request untuk logout token tertentu
    //         $tokenId = $request->token_id;

    //         if ($tokenId) {
    //             // Menghapus token tertentu
    //             $user->tokens()->where('id', $tokenId)->delete();
    //             return response()->json(['message' => $role . ' token revoked successfully.']);
    //         } else {
    //             // Menghapus semua token yang dimiliki user (logout dari semua sesi)
    //             $user->tokens()->delete();
    //             return response()->json(['message' => $role . ' logged out from all devices successfully.']);
    //         }
    //     }

    //     return response()->json(['message' => 'Unauthorized'], 401);
    // }
}
//1|CC0pYdrQeDy69EhGJDtWbdAie3LVXhyeqgf4DWrH
//3|iUXodKLsN8b3P5hgoGeLcVshYpYXaHJt95q1ce39
