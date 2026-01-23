<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{

    public static function generate($iss_user_id){
        $payload = [
            'iss' => APP_NAME,
            'sub' => $iss_user_id,
            'iat' => time(),
            'exp' => time() + JWT_EXPIRY_TIME
        ];

        $token = JWT::encode($payload, JWT_SECRET, JWT_ALGO);
        return $token ;
    }

    public static function handle()
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            self::unauthorized("Token missing");
        }

        $token = str_replace("Bearer ", "", $headers['Authorization']);

        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));

            // storing user id globally for request lifecycle
            $_REQUEST['auth_user_id'] = $decoded->sub;

        } catch (Exception $e) {
            self::unauthorized("Invalid or expired token");
        }
    }

    private static function unauthorized(string $msg)
    {
        http_response_code(401);
        echo json_encode(["error" => $msg]);
        exit;
    }



}
