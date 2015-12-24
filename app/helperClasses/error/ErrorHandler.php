<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This class handles php errors.
 */

require_once __DIR__.'/../Page.php';

abstract class ErrorHandler {
    
    /**
     * Executes when php encounters a fatal error. This is used to show a 'nice' error page.
     */
    public static function fatalErrorHandler() {
        if (error_get_last()['type'] == E_ERROR) {
            $page = new Page();
            $page->data['title'] = 'Er is iets misgegaan';
            $page->data['ErrorNoDescriptionNoLinkView']['errorTitle'] = 'Er is iets misgegaan :(';
            $page->addView('error/ErrorNoDescriptionNoLinkView');
            $page->showBasic();
        }
    }
}