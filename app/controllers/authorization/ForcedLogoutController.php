<?php

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

abstract class ForcedLogoutController implements IController
{
    public static function get()
    {
        $page = new Page();
        $page->data['title'] = 'Geen toegang';
        $page->data['ForcedLogoutView']['reLoginUrl'] = $_SERVER['REQUEST_URI'];
        $page->addView('authorization/ForcedLogoutView');
        $page->showBasic();
    }
    
    public static function post()
    {
    }
}
