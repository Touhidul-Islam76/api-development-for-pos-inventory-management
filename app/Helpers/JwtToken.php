<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class JwtToken
{
    //JWT token create
    public static function createToken( array $userData, int $exp ){

        try{

            $key = config('jwt.jwt_key'); //"fetching secret key from config/jwt.php file"

        $payload = $userData + [
            'iss' => 'Pos_Inventory_Management_System', //issuer name
            'iat' => time(), //issued at
            'exp' => $exp, //expire time
        ];

         $token = JWT::encode( $payload, $key, 'HS512' ); //generating token with HS512 algorithm, with secret key($key) and payload data($payload)
        
         return response()->json([

            'error' => 'false',
            'token' => $token

         ]);


        }catch(\Exception $e){

            Log::critical($e->getMessage().''.$e->getFile().''.$e->getLine());  //here log means saving error message in laravel log file(storage/logs/laravel.log) and critical means error level & it has more level like ( emergency, alert, error, warning, notice, info, debug )

            return response()->json([

                'error' =>'true',
                'message' => 'Token generation failed',
                'details' => $e->getMessage()

            ]);
        }

        
    }


    //JWT token verify
    public static function verifyToken( string $token ){

        try{

            $key = config('jwt.jwt_key'); //"fetching secret key from config/jwt.php file"


            if( !$token ){

                return response()->json([

                    'error' => 'true',
                    'payload' => [],
                    'message' => 'Token not provided'

                 ]);

            }

            $payload = JWT::decode( $token, new Key( ($key), 'HS512' ) ); //"decoding token with HS512 algorithm, with secret key($key) and token($token) &key must be imported from Firebase\JWT\Key"
            return response()->json([

                'error' => 'false',
                'payload' => $payload,
                'message' => 'Token verified successfully'

             ]);

        }catch(\Exception $e){

            Log::critical($e->getMessage().''.$e->getFile().''.$e->getLine());  //here log means saving error message in laravel log file(storage/logs/laravel.log) and critical means error level & it has more level like ( emergency, alert, error, warning, notice, info, debug )

            return response()->json([

                'error' =>'true',
                'payload' => [],
                'message' => 'Token verification failed',
                

            ]);
        }

    }
}
