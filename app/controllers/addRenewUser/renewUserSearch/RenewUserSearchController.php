<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the renew user search page.
 */

require_once __DIR__.'/../../IController.php';
require_once __DIR__.'/../../../helperClasses/Page.php';

require_once __DIR__.'/../../../views/addRenewUser/renewUserSearch/RenewUserSearchViewValidator.php';

require_once __DIR__.'/../../../models/user/User.php';
require_once __DIR__.'/../../../models/user/UserDB.php';
require_once __DIR__.'/../../../models/user/UserDBException.php';

abstract class RenewUserSearchController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Gebruiker hernieuwen';
        RenewUserSearchController::buildRenewUserSearchView($page, false);
        $page->showWithMenu();
    }
    
    public static function post() {
        $page = new Page();
        $page->data['title'] = 'Gebruikers hernieuwen';
        RenewUserSearchController::buildRenewUserSearchView($page, true);
        
        $errMsgs = RenewUserSearchViewValidator::validate($_POST);
        
        //If there are no errors we load the results form, else we load the error message
        if (empty($errMsgs))
            RenewUserSearchController::loadSearchResults($page);
        else
            $page->data['RenewUserSearchView']['errMsgs'] = array_merge($page->data['RenewUserSearchView']['errMsgs'], $errMsgs);
        $page->showWithMenu();
    }
    
    /**
     * Builds the page for the user input.
     * 
     * @param Page $page page to add the views to
     */
    private static function buildRenewUserSearchView(Page $page, $searchMode) {
        $page->data['RenewUserSearchView']['renew_user_search_formAction'] = $_SERVER['REQUEST_URI'];
        
        if ($searchMode) {
            $page->data['RenewUserSearchView']['firstName'] = $_POST['first_name'];
            $page->data['RenewUserSearchView']['lastName'] = $_POST['last_name'];
            $page->data['RenewUserSearchView']['email'] = $_POST['email'];
        }
        else {
            $page->data['RenewUserSearchView']['firstName'] = '';
            $page->data['RenewUserSearchView']['lastName'] = '';
            $page->data['RenewUserSearchView']['email'] = '';
        }
        
        $page->addView('addRenewUser/renewUserSearch/RenewUserSearchView');
    }
    
    /**
     * Get search results and load the data into the page.
     * 
     * @param Page $page
     */
    private static function loadSearchResults($page) {
        try {
            $users = UserDB::getSearchUsers(['firstName' => true, 'lastName' => true, 'email' => true], ['firstName' => $_POST['first_name'], 'lastName' => $_POST['last_name'], 'email' => $_POST['email']], null);
            if (count($users) == 0)
                $page->addView('userSearch/UserSearchNoResultsView');
            else {
                $page->data['RenewUserSearchResultsView']['users'] = $users;
                $page->addView('addRenewUser/renewUserSearch/RenewUserSearchResultsView');
            }
        }
        catch (Exception $ex) {
            $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] = 'Kan gebruikers niet ophalen.';
            $page->addView('error/ErrorMessageNoDescriptionNoLinkView');
        }
    }
}