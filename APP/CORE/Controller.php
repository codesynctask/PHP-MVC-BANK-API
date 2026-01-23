<?php


class Controller{
    function view($fileName)
    {
        $fileName = "../app/view/" . $fileName . ".view.php";

        // loading view files
        if (file_exists($fileName)) {
            require $fileName;
        } else {
            $fileName = "../app/view/404.view.php";
            require $fileName;
        }
    }

    function json_response($response,$status_code=200){
        http_response_code($status_code);
        echo json_encode($response);
    }
}