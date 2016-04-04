<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the page not found page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

abstract class PageNotFoundController implements IController {
    
    public static function get() {
        PageNotFoundController::buildPage();
    }
    
    public static function post() {
        PageNotFoundController::buildPage();
    }
    
    /**
     * Method to build page. We use it so we can handle both get ans post requests.
     */
    private static function buildPage() {
        $page = new Page();
        $page->data['title'] = 'Pagina niet gevonden';
        $page->addView('pageNotFound/PageNotFoundView');
        $page->showWithMenu();
    }
}
