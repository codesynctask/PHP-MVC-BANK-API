<?php

class App
{
    private $controller = "Home";
    private $method = "index";
    public $protectedRoutes = [
        'auth/profile',
    ];
    
    private function getURL()
    {
        $url = $_GET["url"] ?? "home";
        $url = explode("/", trim($url, "/"));
        return $url;
    }

    public function handleController()
    {
        // 1. routing setup according to file
        $url = $this->getURL();
        $filename = "../app/controller/" . ucfirst($url[0]) . ".php";
        if (file_exists($filename)) {
            require $filename;
            $this->controller= ucfirst($url[0]);
        } else {
            $filename = "../app/controller/_404.php";
            require $filename;
            $this->controller= "_404";
            
        }

        // 2. loading controller
        $controller = new $this->controller;

        // 3. loading method
        if(isset($url[1])){
            if (method_exists($controller,$url[1])) {
            $this->method = $url[1];
            }
        }

        // 4. Loading Middleware based on [protected routes]
        $route = strtolower($this->controller . '/' . $this->method);
        if (in_array($route, $this->protectedRoutes)) {
            AuthMiddleware::handle();
        }

        // 5. executing dynamic method of dynamic controller
        call_user_func([$controller,$this->method],[]);


    }
}
