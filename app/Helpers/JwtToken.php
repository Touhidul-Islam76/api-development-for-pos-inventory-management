<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class JwtToken
{
    // Create JWT Token
    public static function createToken(array $userData, int $exp): array
    {
        try {
            $key = config('jwt.jwt_key');

            $payload = $userData + [
                'iss' => 'Pos_Inventory_Management_System',
                'iat' => time(),
                'exp' => $exp,
            ];

            $token = JWT::encode($payload, $key, 'HS256');

            return [
                'error' => false,
                'token' => $token
            ];

        } catch (\Exception $e) {

            Log::critical($e->getMessage().' '.$e->getFile().' '.$e->getLine());

            return [
                'error' => true,
                'message' => 'Token generation failed',
                'details' => $e->getMessage()
            ];
        }
    }


    // Verify JWT Token
    public static function verifyToken(string $token): array
    {
        try {
            $key = config('jwt.jwt_key');

            if (!$token) {
                return [
                    'error' => true,
                    'payload' => [],
                    'message' => 'Token not provided'
                ];
            }

            $payload = JWT::decode($token, new Key($key, 'HS256'));

            return [
                'error' => false,
                'payload' => $payload,
                'message' => 'Token verified successfully'
            ];

        } catch (\Exception $e) {

            Log::critical($e->getMessage().' '.$e->getFile().' '.$e->getLine());

            return [
                'error' => true,
                'payload' => [],
                'message' => 'Token verification failed'
            ];
        }
    }
}
