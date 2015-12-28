<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the home page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

abstract class HomeController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Home';
        $page->addView('home/HomeView');
        $page->showWithMenu();
    }
    
    public static function post() {
    }
}
