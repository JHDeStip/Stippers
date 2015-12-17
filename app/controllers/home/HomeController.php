<?php

require_once __DIR__."/../IController.php";
require_once __DIR__."/../../helperClasses/View.php";

abstract class HomeController implements IController
{
    public static function get()
    {
        $data["title"] = "Home";
        View::showBasicView(["home/HomeView"], $data);
    }
    
    public static function post()
    {
    }
}
