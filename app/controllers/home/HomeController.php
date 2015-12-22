<?php

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

abstract class HomeController implements IController
{
    public static function get()
    {
        $page = new Page();
        $page->data['title'] = 'Home';
        $page->addView('home/HomeView');
        $page->showBasic();
    }
    
    public static function post()
    {
    }
}
