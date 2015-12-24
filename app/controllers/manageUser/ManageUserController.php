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

require_once __DIR__.'/../../views/userSearch/UserSearchBasicViewValidator.php';
require_once __DIR__.'/../../views/userSearch/UserSearchUserManagerViewValidator.php';
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
        if (!isset($_SESSION['Stippers']['UserSearch']['inputData'])) {
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

        $page->showBasic();
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
        
        $page->showBasic();
    }
    
    /**
     * Prepare data for all views and add them to the page.
     * 
     * @param Page $page
     */
    private static function prepUserSearchViews($page) {
        ManageUserController::prepUserSearchTopViewData($page);
        $page->addView('userSearch/UserSearchTopView');
        ManageUserController::prepUserSearchBasicViewData($page);
        $page->addView('userSearch/UserSearchBasicView');
        ManageUserController::prepUserSearchUserManagerViewData($page);
        $page->addView('userSearch/UserSearchUserManagerView');
        if ($_SESSION['Stippers']['user']->isAdmin) {
            ManageUserController::prepUserSearchAdminViewData($page);
            $page->addView('userSearch/UserSearchAdminView');
        }
        ManageUserController::prepUserSearchUserManagerOptionsViewData($page);
        $page->addView('userSearch/UserSearchUserManagerOptionsView');
        $page->addView('userSearch/UserSearchBottomView');
    }
    
    /**
     * Prepares data for view and add view to page.
     * 
     * @param Page $page
     */
    private static function prepUserSearchTopViewData($page) {
        $page->data['UserSearchTopView']['user_search_formAction'] = $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Prepares data for view and add view to page.
     * 
     * @param Page $page
     */
    private static function prepUserSearchBasicViewData($page) {
        $page->data['UserSearchBasicView']['firstName'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['firstName'];
        $page->data['UserSearchBasicView']['lastName'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['lastName'];
        $page->data['UserSearchBasicView']['email'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['email'];
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['firstName'])
            $page->data['UserSearchBasicView']['showFirstNameChecked'] = 'checked';
        else
            $page->data['UserSearchBasicView']['showFirstNameChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['lastName'])
            $page->data['UserSearchBasicView']['showLastNameChecked'] = 'checked';
        else
            $page->data['UserSearchBasicView']['showLastNameChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['email'])
            $page->data['UserSearchBasicView']['showEmailChecked'] = 'checked';
        else
            $page->data['UserSearchBasicView']['showEmailChecked'] = '';
        
        $page->data['UserSearchBasicView']['errMsgs'] = UserSearchBasicViewValidator::initErrMsgs();
    }
    
    /**
     * Prepares data for view and add view to page.
     * 
     * @param Page $page
     */
    private static function prepUserSearchUserManagerViewData($page) {
        $page->data['UserSearchUserManagerView']['balance'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['balance'];
        $page->data['UserSearchUserManagerView']['phone'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['phone'];
        $page->data['UserSearchUserManagerView']['dateOfBirth'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['dateOfBirth'];
        $page->data['UserSearchUserManagerView']['street'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['street'];
        $page->data['UserSearchUserManagerView']['houseNumber'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['houseNumber'];
        $page->data['UserSearchUserManagerView']['city'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['city'];
        $page->data['UserSearchUserManagerView']['postalCode'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['postalCode'];
        $page->data['UserSearchUserManagerView']['country'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['country'];
        $page->data['UserSearchUserManagerView']['membershipYear'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['membershipYear'];
        $page->data['UserSearchUserManagerView']['cardNumber'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['cardNumber'];
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['balance'])
            $page->data['UserSearchUserManagerView']['showBalanceChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showBalanceChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['phone'])
            $page->data['UserSearchUserManagerView']['showPhoneChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showPhoneChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['dateOfBirth'])
            $page->data['UserSearchUserManagerView']['showDateOfBirthChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showDateOfBirthChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['street'])
            $page->data['UserSearchUserManagerView']['showStreetChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showStreetChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['houseNumber'])
            $page->data['UserSearchUserManagerView']['showHouseNumberChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showHouseNumberChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['city'])
            $page->data['UserSearchUserManagerView']['showCityChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showCityChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['postalCode'])
            $page->data['UserSearchUserManagerView']['showPostalCodeChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showPostalCodeChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['country'])
            $page->data['UserSearchUserManagerView']['showCountryChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showCountryChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['membershipYear'])
            $page->data['UserSearchUserManagerView']['showMembershipYearChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showMembershipYearChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['cardNumber'])
            $page->data['UserSearchUserManagerView']['showCardNumberChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showCardNumberChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['creationTime'])
            $page->data['UserSearchUserManagerView']['showCreationTimeChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showCreationTimeChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['nCheckInsPerYear'])
            $page->data['UserSearchUserManagerView']['nCheckInsPerYearChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['nCheckInsPerYearChecked'] = '';
        
        $page->data['UserSearchUserManagerView']['errMsgs'] = UserSearchUserManagerViewValidator::initErrMsgs();
    }
    
    /**
     * Prepares data for view and add view to page.
     * 
     * @param Page $page
     */
    private static function prepUserSearchAdminViewData($page) {
        $page->data['UserSearchAdminView']['isAdmin'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['isAdmin'];
        $page->data['UserSearchAdminView']['isAdminSelected'][''] = '';
        $page->data['UserSearchAdminView']['isAdminSelected']['0'] = '';
        $page->data['UserSearchAdminView']['isAdminSelected']['1'] = '';
        $page->data['UserSearchAdminView']['isAdminSelected'][$_SESSION['Stippers']['UserSearch']['inputData']['values']['isAdmin']] = 'selected';
        
        $page->data['UserSearchAdminView']['isUserManager'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['isUserManager'];
        $page->data['UserSearchAdminView']['isUserManagerSelected'][''] = '';
        $page->data['UserSearchAdminView']['isUserManagerSelected']['0'] = '';
        $page->data['UserSearchAdminView']['isUserManagerSelected']['1'] = '';
        $page->data['UserSearchAdminView']['isUserManagerSelected'][$_SESSION['Stippers']['UserSearch']['inputData']['values']['isUserManager']] = 'selected';
        
        $page->data['UserSearchAdminView']['isAuthorizedBrowserManager'] = $_SESSION['Stippers']['UserSearch']['inputData']['values']['isAuthorizedBrowserManager'];
        $page->data['UserSearchAdminView']['isAuthorizedBrowserManagerSelected'][''] = "";
        $page->data['UserSearchAdminView']['isAuthorizedBrowserManagerSelected']['0'] = "";
        $page->data['UserSearchAdminView']['isAuthorizedBrowserManagerSelected']['1'] = "";
        $page->data['UserSearchAdminView']['isAuthorizedBrowserManagerSelected'][$_SESSION['Stippers']['UserSearch']['inputData']['values']['isAuthorizedBrowserManager']] = 'selected';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['isAdmin'])
            $page->data['UserSearchAdminView']['showIsAdminChecked'] = 'checked';
        else
            $page->data['UserSearchAdminView']['showIsAdminChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['isUserManager'])
            $page->data['UserSearchAdminView']['showIsUserManagerChecked'] = 'checked';
        else
            $page->data['UserSearchAdminView']['showIsUserManagerChecked'] = '';
        
        if ($_SESSION['Stippers']['UserSearch']['inputData']['show']['isAuthorizedBrowserManager'])
            $page->data['UserSearchAdminView']['showIsAuthorizedBrowserManagerChecked'] = 'checked';
        else
            $page->data['UserSearchAdminView']['showIsAuthorizedBrowserManagerChecked'] = '';
        
        $page->data['UserSearchAdminView']['errMsgs'] = UserSearchAdminViewValidator::initErrMsgs();
    }
    
    /**
     * Prepares data for view and add view to page.
     * 
     * @param Page $page
     */
    private static function prepUserSearchUserManagerOptionsViewData($page) {
        if ($_SESSION['Stippers']['UserSearch']['inputData']['options']['orderByBirthday'])
            $page->data['UserSearchUserManagerOptionsView']['orderByBirthdayChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerOptionsView']['orderByBirthdayChecked'] = '';
    }
    
    /**
     * Validate data for views.
     * 
     * @param Page $page
     */
    private static function validateUserSearchViewsData($data) {
        $invalid = false;
        
        $errMsgs = UserSearchBasicViewValidator::validate($data['UserSearchBasicView']);
        if (!empty($errMsgs)) {
            $data['UserSearchBasicView']['errMsgs'] = array_merge($data['UserSearchBasicView']['errMsgs'], $errMsgs);
            $invalid = true;
        }
        
        $errMsgs = UserSearchUserManagerViewValidator::validate($data['UserSearchUserManagerView']);
        if (!empty($errMsgs)) {
            $data['UserSearchUserManagerView']['errMsgs'] = array_merge($data['UserSearchUserManagerView']['errMsgs'], $errMsgs);
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
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['firstName'] = $_POST['first_name'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['firstName'] = '';
        if (isset($_POST['last_name']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['lastName'] = $_POST['last_name'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['lastName'] = '';
        if (isset($_POST['email']))
            $_SESSION['Stippers']['UserSearch']['values']['email'] = $_POST['email'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['email'] = '';
        if (isset($_POST['balance']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['balance'] = $_POST['balance'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['balance'] = '';
        if (isset($_POST['phone']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['phone'] = $_POST['phone'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['phone'] = '';
        if (isset($_POST['date_of_birth']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['dateOfBirth'] = $_POST['date_of_birth'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['dateOfBirth'] = '';
        if (isset($_POST['street']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['street'] = $_POST['street'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['street'] = '';
        if (isset($_POST['house_number']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['houseNumber'] = $_POST['house_number'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['houseNumber'] = '';
        if (isset($_POST['city']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['city'] = $_POST['city'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['city'] = '';
        if (isset($_POST['postalCode']))
            $_SESSION['Stippers']['UserSearch']['inputData']['postalCode'] = $_POST['postalCode'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['postalCode'] = '';
        if (isset($_POST['country']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['country'] = $_POST['country'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['country'] = '';
        if (isset($_POST['membership_year']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['membershipYear'] = $_POST['membership_year'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['membershipYear'] = '';
        if (isset($_POST['card_number']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['cardNumber'] = $_POST['card_number'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['cardNumber'] = '';
        if (isset($_POST['creation_time']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['creationTime'] = $_POST['creation_time'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['creationTime'] = '';
        if (isset($_POST['is_admin']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['isAdmin'] = $_POST['is_admin'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['isAdmin'] = '';
        if (isset($_POST['is_user_manager']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['isUserManager'] = $_POST['is_user_manager'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['isUserManager'] = '';
        if (isset($_POST['is_authorized_browser_manager']))
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['isAuthorizedBrowserManager'] = $_POST['is_authorized_browser_manager'];
        else
            $_SESSION['Stippers']['UserSearch']['inputData']['values']['isAuthorizedBrowserManager'] = '';
        
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['firstName'] = isset($_POST['show_first_name']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['lastName'] = isset($_POST['show_last_name']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['email'] = isset($_POST['show_email']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['balance'] = isset($_POST['show_balance']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['phone'] = isset($_POST['show_phone']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['dateOfBirth'] = isset($_POST['show_date_of_birth']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['street'] = isset($_POST['show_street']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['houseNumber'] = isset($_POST['show_house_number']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['city'] = isset($_POST['show_city']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['postalCode'] = isset($_POST['show_postal_code']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['country'] = isset($_POST['show_country']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['membershipYear'] = isset($_POST['show_membership_year']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['cardNumber'] = isset($_POST['show_card_number']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['creationTime'] = isset($_POST['show_creation_time']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['nCheckInsPerYear'] = isset($_POST['n_check_ins_per_year']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['isAdmin'] = isset($_POST['show_is_admin']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['isUserManager'] = isset($_POST['show_is_user_manager']);
        $_SESSION['Stippers']['UserSearch']['inputData']['show']['isAuthorizedBrowserManager'] = isset($_POST['show_is_authorized_browser_manager']);
        
        $_SESSION['Stippers']['UserSearch']['inputData']['options']['orderByBirthday'] = isset($_POST['order_by_birthday']);
    }
    
    /**
     * Get search results and load the data into the page.
     * 
     * @param Page $page
     */
    private static function loadSearchResults($page) {
        try {
            $users = UserDB::getSearchUsers($_SESSION['Stippers']['UserSearch']['inputData']['show'], $_SESSION['Stippers']['UserSearch']['inputData']['values'], $_SESSION['Stippers']['UserSearch']['inputData']['options']);
            if (count($users) == 0)
                $page->addView('userSearch/UserSearchNoResultsView');
            else {
                $page->data['UserSearchResultsView']['users'] = $users;
                $page->addView('userSearch/UserSearchResultsView');
            }
        }
        catch (Exception $ex) {
            $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] = 'Kan gebruikers niet ophalen.';
            array_push($views, 'error/ErrorMessageNoDescriptionNoLinkView');
        }
    }
}