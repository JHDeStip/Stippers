<?php

require_once __DIR__."/../IController.php";
require_once __DIR__."/../../helperClasses/View.php";
require_once __DIR__."/../../config/DomainConfig.php";


abstract class LogoutController implements IController
{
    public static function get()
    {
        session_destroy();
        $data["title"] = "Afmelden";
        $data["LogoutView"]["homeUrl"] = DomainConfig::DOMAINSUFFIX."home";
        View::showBasicView(["authorization/LogoutView"], $data);
    }
    
    public static function post()
    {
    }
}
