<?php

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

abstract class PageNotFoundController implements IController {
    public static function get() {
        PageNotFoundController::buildPage();
    }
    
    public static function post() {
        PageNotFoundController::buildPage();
    }
    
    private static function buildPage() {
        $page = new Page();
        $page->data['title'] = 'Pagina niet gevonden';
        $page->addView('pageNotFound/PageNotFoundView');
        $page->showBasic();
    }
}
