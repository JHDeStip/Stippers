<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for when the an error occured when talking the database.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

abstract class DBErrorController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Database error';
        $page->data['DBErrorView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
        $page->addView('authorization/DBErrorView');
        $page->showBasic();
    }
    
    public static function post() {
    }
}
