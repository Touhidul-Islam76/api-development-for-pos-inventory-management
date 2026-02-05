<?php

namespace App\Http\Middleware;

use App\Helpers\JwtToken;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class JwtVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('loginToken');

        if (!$token) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized: No token',
            ], 401);
        }

        $decode = JwtToken::verifyToken($token);

        if ($decode['error']) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized: ' . $decode['message'],
            ], 401);
        }

        $payload = $decode['payload'];

        $user = User::where('id', $payload->id)
            ->where('email', $payload->email)
            ->first();

        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized: User not found',
            ], 401);
        }

        // 🔥 THIS IS THE FIX
        Auth::login($user);

        return $next($request);
    }
}
