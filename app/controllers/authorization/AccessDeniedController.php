<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for when the access to a page is denied.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

abstract class AccessDeniedController implements IController {
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Geen toegang';
        $page->addView('authorization/AccessDeniedView');
        $page->showBasic();
    }
    
    public static function post() {
    }
}
