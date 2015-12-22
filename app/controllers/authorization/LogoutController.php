<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the logout page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';
require_once __DIR__.'/../../config/DomainConfig.php';


abstract class LogoutController implements IController {
    
    public static function get() {
        //Logging out means we simply destroy the session
        session_destroy();
        $page = new Page();
        $page->data['title'] = 'Afmelden';
        $page->data['LogoutView']['homeUrl'] = DomainConfig::DOMAINSUFFIX.'home';
        $page->addView('authorization/LogoutView');
        $page->showBasic();
    }
    
    public static function post() {
    }
}
