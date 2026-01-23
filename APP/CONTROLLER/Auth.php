<?php

use Firebase\JWT\JWT;

class Auth extends Controller
{
    public function index(){
        echo "Auth controller";
    }

    public function register(){
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->json_response(["msg" => "Method not allowed"]);
            return;
        }
        $newUser = [
            "name"=> $_POST["name"],
            "email"=> $_POST["email"],
            "password"=> $_POST["password"],
            "role"=> $_POST["role"],
            "status"=> $_POST["status"],
        ];
        $user = new User();
        $user->create($newUser);
        
        $this->json_response(["msg" => "User Created","newUserData"=>$newUser]);
    }
        

    public function login(){
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->json_response(["msg" => "Method not allowed"],405);
            return;
        }

        // 1. checking login user data received
        $email = $_POST["email"] ?? null;
        $password = $_POST["password"] ?? null;

        if (!$email || !$password) {
            $this->json_response(["msg" => "Email and password required"],400);
            return;
        }
                
        // 2. checking credientials with DB
        $user = new User();
        $userData = $user->findOneBy("email",$email);
        
        if (!$userData) {
            $this->json_response(["msg" => "Invalid credentials"],401);
            return;
        }
        
        $userPassword = $userData["password"]; //rehash
        $userEmail = $userData["email"];

        if (!($userPassword===$password)) { 
            $this->json_response(["msg" => "Invalid credentials"],401);
            return;
        }

        // 3. Generating JWT token
        $token = AuthMiddleware::generate($userData["id"]);
        $this->json_response([
            "token" => $token
        ],200);
    }

    public function profile()
    {
        if (!isset($_REQUEST['auth_user_id'])) {
            $this->json_response(["msg" => "Unauthorized"], 401);
            return;
        }

        $userId = $_REQUEST['auth_user_id'];
        $user = new User();

        switch ($_SERVER["REQUEST_METHOD"]) {

            case 'GET':
                $userData = $user->findOneBy("id", $userId);

                if (!$userData) {
                    $this->json_response(["msg" => "User not found"], 404);
                    return;
                }

                unset($userData["password"]);
                $this->json_response($userData, 200);
                break;

            case 'PUT':
                $this->json_response([
                    "msg" => "Profile update endpoint"
                ], 200);
                break;

            case 'PATCH':
                $this->json_response([
                    "msg" => "Profile PATCH endpoint"
                ], 200);
                break;

            case 'DELETE':
                $this->json_response([
                    "msg" => "Profile DELETE endpoint"
                ], 200);
                break;

            default:
                $this->json_response(["msg" => "Method not allowed"], 405);
                break;
        }
    }


}


