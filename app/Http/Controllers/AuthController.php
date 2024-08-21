<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
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
    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         $user = Auth::user();
    //         $tokenResult = $user->createToken('MyApp');
    //         $token = $tokenResult->plainTextToken;

    //         // Atur waktu kedaluwarsa
    //         $tokenResult->accessToken->expires_at = Carbon::now()->addHours(2);
    //         $tokenResult->accessToken->save();
    //         $success['token'] =  $token;
    //         $success['name'] =  $user->name;

    //         return $this->sendResponse($success, 'User login successfully.');
    //     } else {
    //         return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
    //     }
    // }

    public function login(Request $request)
    {
        // Tentukan apakah login untuk admin atau user
        if ($this->isAdminLogin($request)) {
            // Logika login untuk Admin (User model)
            $credentials = $request->only('email', 'password');

            $user = User::where('email', $credentials['email'])->first();

            if ($user && Auth::attempt($credentials)) {
                $tokenResult = $user->createToken('MyApp', ['admin']);
                $token = $tokenResult->plainTextToken;

                // Atur waktu kedaluwarsa token
                $tokenResult->accessToken->expires_at = Carbon::now()->addHours(2);
                $tokenResult->accessToken->save();

                $success['token'] = $token;
                $success['name'] = $user->name;

                return $this->sendResponse($success, 'Admin login successfully.');
            }
        } else {
            // Logika login untuk User (Karyawan model)
            $credentials = $request->only('no_badge', 'password');


            $user = Karyawan::where('no_badge', $credentials['no_badge'])
                ->first();

            if ($user && Auth::guard('karyawan')->attempt($credentials)) {
                $tokenResult = $user->createToken('MyApp', ['karyawan']);
                $token = $tokenResult->plainTextToken;

                // Atur waktu kedaluwarsa token
                $tokenResult->accessToken->expires_at = Carbon::now()->addHours(2);
                $tokenResult->accessToken->save();

                $success['token'] = $token;
                $success['name'] = $user->name;

                return $this->sendResponse($success, 'User login successfully.');
            }else{
            }
        }
        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'],401);

        // Jika gagal login
        
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
        $user = Auth::user();

        if ($user) {
            // Cek peran user apakah admin atau karyawan
            if ($user instanceof User) {
                // Proses logout untuk admin
                $role = 'Admin';
            } elseif ($user instanceof Karyawan) {
                // Proses logout untuk karyawan
                $role = 'Karyawan';
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Cek apakah token ID ada dalam request untuk logout token tertentu
            $tokenId = $request->token_id;

            if ($tokenId) {
                // Menghapus token tertentu
                $user->tokens()->where('id', $tokenId)->delete();
                return response()->json(['message' => $role . ' token revoked successfully.']);
            } else {
                // Menghapus semua token yang dimiliki user (logout dari semua sesi)
                $user->tokens()->delete();
                return response()->json(['message' => $role . ' logged out from all devices successfully.']);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
//1|CC0pYdrQeDy69EhGJDtWbdAie3LVXhyeqgf4DWrH
//3|iUXodKLsN8b3P5hgoGeLcVshYpYXaHJt95q1ce39
