<?php

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

abstract class DBErrorController implements IController
{
    public static function get()
    {
        $page = new Page();
        $page->data['title'] = 'Database error';
        $page->data['DBErrorView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
        $page->addView('authorization/DBErrorView');
        $page->showBasic();
    }
    
    public static function post()
    {
    }
}
