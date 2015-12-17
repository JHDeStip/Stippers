<?php

require_once __DIR__."/../IController.php";
require_once __DIR__."/../../helperClasses/View.php";

abstract class DBErrorController implements IController
{
    public static function get()
    {
        $data["title"] = "Database error";
        $data['DBErrorView']['tryAgainUrl'] = $_SERVER["REQUEST_URI"];
        View::showBasicView(["authorization/DBErrorView"], $data);
    }
    
    public static function post()
    {
    }
}
