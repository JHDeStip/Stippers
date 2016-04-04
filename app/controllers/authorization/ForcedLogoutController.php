<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for when the user is force loged out.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

abstract class ForcedLogoutController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Geen toegang';
        $page->data['ForcedLogoutView']['reLoginUrl'] = $_SERVER['REQUEST_URI'];
        $page->addView('authorization/ForcedLogoutView');
        $page->showBasic();
    }
    
    public static function post() {
    }
}
