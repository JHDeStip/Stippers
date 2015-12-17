<?php

require_once __DIR__."/../IController.php";
require_once __DIR__."/../../helperClasses/View.php";

abstract class ForcedLogoutController implements IController
{
    public static function get()
    {
        $data["title"] = "Geen toegang";
        $data["ForcedLogoutView"]["reLoginUrl"] = $_SERVER["REQUEST_URI"];
        View::showBasicView(["authorization/ForcedLogoutView"], $data);
    }
    
    public static function post()
    {
    }
}
