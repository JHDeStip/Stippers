<?php

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

abstract class AccessDeniedController implements IController
{
    public static function get()
    {
        $page = new Page();
        $page->data['title'] = 'Geen toegang';
        $page->addView('authorization/AccessDeniedView');
        $page->showBasic();
    }
    
    public static function post()
    {
    }
}
