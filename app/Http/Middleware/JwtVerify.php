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
        if( !$request->cookie('loginToken') ){

            return response()->json([

                'error' => true,
                'message' => 'Unauthorized: No token provided',

            ], 401);

        }


        $decode = JwtToken::verifyToken( $request->cookie('loginToken') );

        if($decode['error'] == true){

            return response()->json([

                'error' => true,
                'message' => 'Unauthorized: '.$decode['message'],

            ], 401);

        }

        $payload = $decode['payload'];
        $user = User::where('email', $payload->email)->where( 'id', $payload->id )->first();

        Auth::setUser($user);
        return $next($request);

    }
}
