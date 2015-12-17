<?php

require_once __DIR__."/../IController.php";
require_once __DIR__."/../../helperClasses/View.php";

abstract class AccessDeniedController implements IController
{
    public static function get()
    {
        $data["title"] = "Geen toegang";
        View::showBasicView(["authorization/AccessDeniedView"], $data);
    }
    
    public static function post()
    {
    }
}
