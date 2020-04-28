<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        list($authorization, $token) = array_merge(explode("Bearer", $request->header("Authorization")), array(null));
        if(empty($token)) {
            return response()->json([
                'error' => [
                    'msg' => 'Unauthorized'
                ]
            ], 401);
        }
        
        $token = trim($token);
        list($userId, $random) = explode('#', base64_decode($token));
        if(empty($userId)) {
            return response()->json([
                'error' => [
                    'msg' => 'Invalid token'
                ]
            ], 401);
        }

        $user = User::findOrFail($userId);
        Auth::setUser($user);

        return $next($request);
    }
}
