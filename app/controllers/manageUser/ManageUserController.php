<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the manage user page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../views/userSearch/UserSearchTopViewValidator.php';
require_once __DIR__.'/../../views/userSearch/UserSearchAdminViewValidator.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

abstract class ManageUserController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Gebruikers beheren';
        
        //If we don't have search variables in our session we load empty data
        //in session (no post is et) and repare the views.
        if (!isset($_SESSION['Stippers']['ManageUserSearch']['inputData'])) {
            ManageUserController::loadDataInSession();
            ManageUserController::prepUserSearchViews($page);
        }
        else {
            //If we do have data in our session we prepare the pages,
            //verify the data and if it is valid we load the search results
            //in the page.
            ManageUserController::prepUserSearchViews($page);
            $invalid = ManageUserController::validateUserSearchViewsData($page->data);
            if (!$invalid)
                ManageUserController::loadSearchResults($page);
        }

        $page->showWithMenu();
    }
    
    public static function post() {
        ManageUserController::loadDataInSession();
        
        $page = new Page();
        $page->data['title'] = 'Gebruikers beheren';
        ManageUserController::prepUserSearchViews($page);
        
        //Validate data and if valid load results in page
        $invalid = ManageUserController::validateUserSearchViewsData($page->data);
        
        if (!$invalid)
            ManageUserController::loadSearchResults($page);
        
        $page->showWithMenu();
    }
    
    /**
     * Prepare data for all views and add them to the page.
     * 
     * @param Page $page
     */
    private static function prepUserSearchViews($page) {
        ManageUserController::prepUserSearchTopViewData($page);
        $page->addView('userSearch/UserSearchTopView');
        if ($_SESSION['Stippers']['user']->isAdmin) {
            ManageUserController::prepUserSearchAdminViewData($page);
            $page->addView('userSearch/UserSearchAdminView');
        }
        ManageUserController::prepUserSearchOptionsViewData($page);
        $page->addView('userSearch/UserSearchOptionsView');
        $page->addView('userSearch/UserSearchBottomView');
    }
    
    /**
     * Prepares data for view and add view to page.
     * 
     * @param Page $page
     */
    private static function prepUserSearchTopViewData($page) {
        $page->data['UserSearchTopView']['user_search_formAction'] = $_SERVER['REQUEST_URI'];
        
        $page->data['UserSearchTopView']['firstName'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['firstName'];
        $page->data['UserSearchTopView']['lastName'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['lastName'];
        $page->data['UserSearchTopView']['email'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['email'];
        $page->data['UserSearchTopView']['balance'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['balance'];
        $page->data['UserSearchTopView']['phone'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['phone'];
        $page->data['UserSearchTopView']['dateOfBirth'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['dateOfBirth'];
        $page->data['UserSearchTopView']['street'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['street'];
        $page->data['UserSearchTopView']['houseNumber'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['houseNumber'];
        $page->data['UserSearchTopView']['city'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['city'];
        $page->data['UserSearchTopView']['postalCode'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['postalCode'];
        $page->data['UserSearchTopView']['country'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['country'];
        $page->data['UserSearchTopView']['membershipYear'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['membershipYear'];
        $page->data['UserSearchTopView']['cardNumber'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['cardNumber'];
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['firstName'])
            $page->data['UserSearchTopView']['showFirstNameChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showFirstNameChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['lastName'])
            $page->data['UserSearchTopView']['showLastNameChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showLastNameChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['email'])
            $page->data['UserSearchTopView']['showEmailChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showEmailChecked'] = '';
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['balance'])
            $page->data['UserSearchTopView']['showBalanceChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showBalanceChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['phone'])
            $page->data['UserSearchTopView']['showPhoneChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showPhoneChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['dateOfBirth'])
            $page->data['UserSearchTopView']['showDateOfBirthChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showDateOfBirthChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['street'])
            $page->data['UserSearchTopView']['showStreetChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showStreetChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['houseNumber'])
            $page->data['UserSearchTopView']['showHouseNumberChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showHouseNumberChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['city'])
            $page->data['UserSearchTopView']['showCityChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showCityChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['postalCode'])
            $page->data['UserSearchTopView']['showPostalCodeChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showPostalCodeChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['country'])
            $page->data['UserSearchTopView']['showCountryChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showCountryChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['membershipYear'])
            $page->data['UserSearchTopView']['showMembershipYearChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showMembershipYearChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['cardNumber'])
            $page->data['UserSearchTopView']['showCardNumberChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showCardNumberChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['creationTime'])
            $page->data['UserSearchTopView']['showCreationTimeChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['showCreationTimeChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['nCheckInsPerYear'])
            $page->data['UserSearchTopView']['nCheckInsPerYearChecked'] = 'checked';
        else
            $page->data['UserSearchTopView']['nCheckInsPerYearChecked'] = '';
        
        $page->data['UserSearchTopView']['errMsgs'] = UserSearchTopViewValidator::initErrMsgs();
    }
    
    /**
     * Prepares data for view and add view to page.
     * 
     * @param Page $page
     */
    private static function prepUserSearchAdminViewData($page) {
        $page->data['UserSearchAdminView']['isAdmin'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isAdmin'];
        $page->data['UserSearchAdminView']['isAdminSelected'][''] = '';
        $page->data['UserSearchAdminView']['isAdminSelected']['0'] = '';
        $page->data['UserSearchAdminView']['isAdminSelected']['1'] = '';
        $page->data['UserSearchAdminView']['isAdminSelected'][$_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isAdmin']] = 'selected';
        
        $page->data['UserSearchAdminView']['isUserManager'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isUserManager'];
        $page->data['UserSearchAdminView']['isUserManagerSelected'][''] = '';
        $page->data['UserSearchAdminView']['isUserManagerSelected']['0'] = '';
        $page->data['UserSearchAdminView']['isUserManagerSelected']['1'] = '';
        $page->data['UserSearchAdminView']['isUserManagerSelected'][$_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isUserManager']] = 'selected';
        
        $page->data['UserSearchAdminView']['isBrowserManager'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isBrowserManager'];
        $page->data['UserSearchAdminView']['isBrowserManagerSelected'][''] = '';
        $page->data['UserSearchAdminView']['isBrowserManagerSelected']['0'] = '';
        $page->data['UserSearchAdminView']['isBrowserManagerSelected']['1'] = '';
        $page->data['UserSearchAdminView']['isBrowserManagerSelected'][$_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isBrowserManager']] = 'selected';
        
        $page->data['UserSearchAdminView']['isMoneyManager'] = $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isMoneyManager'];
        $page->data['UserSearchAdminView']['isMoneyManagerSelected'][''] = '';
        $page->data['UserSearchAdminView']['isMoneyManagerSelected']['0'] = '';
        $page->data['UserSearchAdminView']['isMoneyManagerSelected']['1'] = '';
        $page->data['UserSearchAdminView']['isMoneyManagerSelected'][$_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isMoneyManager']] = 'selected';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['isAdmin'])
            $page->data['UserSearchAdminView']['showIsAdminChecked'] = 'checked';
        else
            $page->data['UserSearchAdminView']['showIsAdminChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['isUserManager'])
            $page->data['UserSearchAdminView']['showIsUserManagerChecked'] = 'checked';
        else
            $page->data['UserSearchAdminView']['showIsUserManagerChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['isBrowserManager'])
            $page->data['UserSearchAdminView']['showIsBrowserManagerChecked'] = 'checked';
        else
            $page->data['UserSearchAdminView']['showIsBrowserManagerChecked'] = '';
        
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['isMoneyManager'])
            $page->data['UserSearchAdminView']['showIsMoneyManagerChecked'] = 'checked';
        else
            $page->data['UserSearchAdminView']['showIsMoneyManagerChecked'] = '';
        
        $page->data['UserSearchAdminView']['errMsgs'] = UserSearchAdminViewValidator::initErrMsgs();
    }
    
    /**
     * Prepares data for view and add view to page.
     * 
     * @param Page $page
     */
    private static function prepUserSearchOptionsViewData($page) {
        if ($_SESSION['Stippers']['ManageUserSearch']['inputData']['options']['orderByBirthday'])
            $page->data['UserSearchOptionsView']['orderByBirthdayChecked'] = 'checked';
        else
            $page->data['UserSearchOptionsView']['orderByBirthdayChecked'] = '';
    }
    
    /**
     * Validate data for views.
     * 
     * @param Page $page
     */
    private static function validateUserSearchViewsData($data) {
        $invalid = false;
        
        $errMsgs = UserSearchTopViewValidator::validate($data['UserSearchTopView']);
        if (!empty($errMsgs)) {
            $data['UserSearchTopView']['errMsgs'] = array_merge($data['UserSearchTopView']['errMsgs'], $errMsgs);
            $invalid = true;
        }
        
        if ($_SESSION['Stippers']['user']->isAdmin) {
            $errMsgs = UserSearchAdminViewValidator::validate($data['UserSearchAdminView']);
            if (!empty($errMsgs)) {
                $data['UserSearchAdminView']['errMsgs'] = array_merge($data['UserSearchAdminView']['errMsgs'], $errMsgs);
                $invalid = true;
            }
        }
        
        return $invalid;
    }
    
    /**
     * Load post data into session.
     * If a post variable is not set the empty string will be loaded.
     */
    private static function loadDataInSession() {
        if (isset($_POST['first_name']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['firstName'] = $_POST['first_name'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['firstName'] = '';
        if (isset($_POST['last_name']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['lastName'] = $_POST['last_name'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['lastName'] = '';
        if (isset($_POST['email']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['email'] = $_POST['email'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['email'] = '';
        if (isset($_POST['balance']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['balance'] = $_POST['balance'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['balance'] = '';
        if (isset($_POST['phone']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['phone'] = $_POST['phone'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['phone'] = '';
        if (isset($_POST['date_of_birth']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['dateOfBirth'] = $_POST['date_of_birth'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['dateOfBirth'] = '';
        if (isset($_POST['street']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['street'] = $_POST['street'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['street'] = '';
        if (isset($_POST['house_number']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['houseNumber'] = $_POST['house_number'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['houseNumber'] = '';
        if (isset($_POST['city']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['city'] = $_POST['city'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['city'] = '';
        if (isset($_POST['postalCode']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['postalCode'] = $_POST['postalCode'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['postalCode'] = '';
        if (isset($_POST['country']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['country'] = $_POST['country'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['country'] = '';
        if (isset($_POST['membership_year']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['membershipYear'] = $_POST['membership_year'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['membershipYear'] = '';
        if (isset($_POST['card_number']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['cardNumber'] = $_POST['card_number'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['cardNumber'] = '';
        if (isset($_POST['creation_time']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['creationTime'] = $_POST['creation_time'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['creationTime'] = '';
        if (isset($_POST['is_admin']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isAdmin'] = $_POST['is_admin'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isAdmin'] = '';
        if (isset($_POST['is_user_manager']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isUserManager'] = $_POST['is_user_manager'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isUserManager'] = '';
        if (isset($_POST['is_browser_manager']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isBrowserManager'] = $_POST['is_browser_manager'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isBrowserManager'] = '';
        if (isset($_POST['is_money_manager']))
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isMoneyManager'] = $_POST['is_money_manager'];
        else
            $_SESSION['Stippers']['ManageUserSearch']['inputData']['values']['isMoneyManager'] = '';
        
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['firstName'] = isset($_POST['show_first_name']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['lastName'] = isset($_POST['show_last_name']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['email'] = isset($_POST['show_email']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['balance'] = isset($_POST['show_balance']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['phone'] = isset($_POST['show_phone']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['dateOfBirth'] = isset($_POST['show_date_of_birth']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['street'] = isset($_POST['show_street']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['houseNumber'] = isset($_POST['show_house_number']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['city'] = isset($_POST['show_city']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['postalCode'] = isset($_POST['show_postal_code']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['country'] = isset($_POST['show_country']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['membershipYear'] = isset($_POST['show_membership_year']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['cardNumber'] = isset($_POST['show_card_number']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['creationTime'] = isset($_POST['show_creation_time']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['nCheckInsPerYear'] = isset($_POST['n_check_ins_per_year']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['isAdmin'] = isset($_POST['show_is_admin']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['isUserManager'] = isset($_POST['show_is_user_manager']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['isBrowserManager'] = isset($_POST['show_is_browser_manager']);
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['show']['isMoneyManager'] = isset($_POST['show_is_money_manager']);
        
        $_SESSION['Stippers']['ManageUserSearch']['inputData']['options']['orderByBirthday'] = isset($_POST['order_by_birthday']);
    }
    
    /**
     * Get search results and load the data into the page.
     * 
     * @param Page $page
     */
    private static function loadSearchResults($page) {
        try {
            $users = UserDB::getSearchUsers($_SESSION['Stippers']['ManageUserSearch']['inputData']['show'], $_SESSION['Stippers']['ManageUserSearch']['inputData']['values'], $_SESSION['Stippers']['ManageUserSearch']['inputData']['options']);
            if (count($users) == 0)
                $page->addView('userSearch/UserSearchNoResultsView');
            else {
                $page->data['UserSearchResultsView']['users'] = $users;
                $page->addView('userSearch/UserSearchResultsView');
            }
        }
        catch (Exception $ex) {
            $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] = 'Kan gebruikers niet ophalen.';
            $page->addView('error/ErrorMessageNoDescriptionNoLinkView');
        }
    }
}