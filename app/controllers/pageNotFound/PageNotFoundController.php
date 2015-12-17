<?php

require_once __DIR__."/../IController.php";
require_once __DIR__."/../../helperClasses/View.php";

abstract class PageNotFoundController implements IController
{
    public static function get()
    {
        $data["title"] = "Pagina niet gevonden";
        View::showBasicView(["pageNotFound/PageNotFoundView"], $data);
    }
    
    public static function post()
    {
    }
}
