<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        
        $user = Auth::user();

        if ($user) {
            $tokenExpiration = $user->tokens->first()->expires_at;

            if ($tokenExpiration && Carbon::now()->greaterThan($tokenExpiration)) {
            //     // Token telah kedaluwarsa
            //     // $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
            
                return response()->json([
                    'message' => 'Token has expired. Please log in again.',
                    'data'=>[
                        'request'=>$request->all(),
                        'dateNow'=>\Carbon\Carbon::now()->translatedFormat('Y-m-d H:i:s'),
                        'tokenExpiration'=>$tokenExpiration,
                        'persamaan'=>\Carbon\Carbon::now()->translatedFormat('Y-m-d H:i:s') > $tokenExpiration
                    ]
                ], 401);
            }
        }
        return $next($request);


        
    }
}

