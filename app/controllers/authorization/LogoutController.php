<?php

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';
require_once __DIR__.'/../../config/DomainConfig.php';


abstract class LogoutController implements IController
{
    public static function get()
    {
        session_destroy();
        $page = new Page();
        $page->data['title'] = 'Afmelden';
        $page->data['LogoutView']['homeUrl'] = DomainConfig::DOMAINSUFFIX.'home';
        $page->addView('authorization/LogoutView');
        $page->showBasic();
    }
    
    public static function post()
    {
    }
}
