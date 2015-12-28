<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the add or renew user page.
 */

require_once __DIR__.'/../../IController.php';
require_once __DIR__.'/../../../helperClasses/Page.php';

abstract class AddOrRenewUserController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Gebruiker toevoegen of hernieuwen';
        $page->data['AddOrRenewUserView']['add_or_renew_user_formAction'] = $_SERVER['REQUEST_URI'];
        $page->addView('addRenewUser/AddOrRenewUserView');
        $page->showWithMenu();
    }
    
    public static function post() {
        if (isset($_POST['yes']))
            header('Location: renewusersearch', true, 303);
        else
            header('Location: adduser', true, 303);
    }
    
}