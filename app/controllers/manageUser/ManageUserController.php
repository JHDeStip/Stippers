<?php

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
        
        if (!isset($_SESSION['stippersUserSearchVars'])) {
            ManageUserController::loadDataInSession();
            ManageUserController::prepUserSearchViews($page);
        }
        else {
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
        
        $invalid = ManageUserController::validateUserSearchViewsData($page->data);
        
        if (!$invalid)
            ManageUserController::loadSearchResults($page);
        
        $page->showBasic();
    }
    
    private static function prepUserSearchViews($page) {
        ManageUserController::prepUserSearchTopViewData($page);
        $page->addView('userSearch/UserSearchTopView');
        ManageUserController::prepUserSearchBasicViewData($page);
        $page->addView('userSearch/UserSearchBasicView');
        ManageUserController::prepUserSearchUserManagerViewData($page);
        $page->addView('userSearch/UserSearchUserManagerView');
        if ($_SESSION['stippersUser']->isAdmin) {
            ManageUserController::prepUserSearchAdminViewData($page);
            $page->addView('userSearch/UserSearchAdminView');
        }
        ManageUserController::prepUserSearchUserManagerOptionsViewData($page);
        $page->addView('userSearch/UserSearchUserManagerOptionsView');
        $page->addView('userSearch/UserSearchBottomView');
    }
    
    private static function prepUserSearchTopViewData($page) {
        $page->data['UserSearchTopView']['user_search_formAction'] = $_SERVER['REQUEST_URI'];
    }
    
    private static function prepUserSearchBasicViewData($page) {
        $page->data['UserSearchBasicView']['firstName'] = $_SESSION['stippersUserSearchVars']['values']['firstName'];
        $page->data['UserSearchBasicView']['lastName'] = $_SESSION['stippersUserSearchVars']['values']['lastName'];
        $page->data['UserSearchBasicView']['email'] = $_SESSION['stippersUserSearchVars']['values']['email'];
        
        if ($_SESSION['stippersUserSearchVars']['show']['firstName'])
            $page->data['UserSearchBasicView']['showFirstNameChecked'] = 'checked';
        else
            $page->data['UserSearchBasicView']['showFirstNameChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['lastName'])
            $page->data['UserSearchBasicView']['showLastNameChecked'] = 'checked';
        else
            $page->data['UserSearchBasicView']['showLastNameChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['email'])
            $page->data['UserSearchBasicView']['showEmailChecked'] = 'checked';
        else
            $page->data['UserSearchBasicView']['showEmailChecked'] = '';
        
        $page->data['UserSearchBasicView']['errMsgs'] = UserSearchBasicViewValidator::initErrMsgs();
    }
    
    private static function prepUserSearchUserManagerViewData($page) {
        $page->data['UserSearchUserManagerView']['balance'] = $_SESSION['stippersUserSearchVars']['values']['balance'];
        $page->data['UserSearchUserManagerView']['phone'] = $_SESSION['stippersUserSearchVars']['values']['phone'];
        $page->data['UserSearchUserManagerView']['dateOfBirth'] = $_SESSION['stippersUserSearchVars']['values']['dateOfBirth'];
        $page->data['UserSearchUserManagerView']['street'] = $_SESSION['stippersUserSearchVars']['values']['street'];
        $page->data['UserSearchUserManagerView']['houseNumber'] = $_SESSION['stippersUserSearchVars']['values']['houseNumber'];
        $page->data['UserSearchUserManagerView']['city'] = $_SESSION['stippersUserSearchVars']['values']['city'];
        $page->data['UserSearchUserManagerView']['postalCode'] = $_SESSION['stippersUserSearchVars']['values']['postalCode'];
        $page->data['UserSearchUserManagerView']['country'] = $_SESSION['stippersUserSearchVars']['values']['country'];
        $page->data['UserSearchUserManagerView']['membershipYear'] = $_SESSION['stippersUserSearchVars']['values']['membershipYear'];
        $page->data['UserSearchUserManagerView']['cardNumber'] = $_SESSION['stippersUserSearchVars']['values']['cardNumber'];
        
        if ($_SESSION['stippersUserSearchVars']['show']['balance'])
            $page->data['UserSearchUserManagerView']['showBalanceChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showBalanceChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['phone'])
            $page->data['UserSearchUserManagerView']['showPhoneChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showPhoneChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['dateOfBirth'])
            $page->data['UserSearchUserManagerView']['showDateOfBirthChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showDateOfBirthChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['street'])
            $page->data['UserSearchUserManagerView']['showStreetChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showStreetChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['houseNumber'])
            $page->data['UserSearchUserManagerView']['showHouseNumberChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showHouseNumberChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['city'])
            $page->data['UserSearchUserManagerView']['showCityChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showCityChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['postalCode'])
            $page->data['UserSearchUserManagerView']['showPostalCodeChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showPostalCodeChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['country'])
            $page->data['UserSearchUserManagerView']['showCountryChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showCountryChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['membershipYear'])
            $page->data['UserSearchUserManagerView']['showMembershipYearChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showMembershipYearChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['cardNumber'])
            $page->data['UserSearchUserManagerView']['showCardNumberChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showCardNumberChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['creationTime'])
            $page->data['UserSearchUserManagerView']['showCreationTimeChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['showCreationTimeChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['nCheckInsPerYear'])
            $page->data['UserSearchUserManagerView']['nCheckInsPerYearChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerView']['nCheckInsPerYearChecked'] = '';
        
        $page->data['UserSearchUserManagerView']['errMsgs'] = UserSearchUserManagerViewValidator::initErrMsgs();
    }
    
    private static function prepUserSearchAdminViewData($page) {
        $page->data['UserSearchAdminView']['isAdmin'] = $_SESSION['stippersUserSearchVars']['values']['isAdmin'];
        $page->data['UserSearchAdminView']['isAdminSelected'][''] = '';
        $page->data['UserSearchAdminView']['isAdminSelected']['0'] = '';
        $page->data['UserSearchAdminView']['isAdminSelected']['1'] = '';
        $page->data['UserSearchAdminView']['isAdminSelected'][$_SESSION['stippersUserSearchVars']['values']['isAdmin']] = 'selected';
        
        $page->data['UserSearchAdminView']['isUserManager'] = $_SESSION['stippersUserSearchVars']['values']['isUserManager'];
        $page->data['UserSearchAdminView']['isUserManagerSelected'][''] = '';
        $page->data['UserSearchAdminView']['isUserManagerSelected']['0'] = '';
        $page->data['UserSearchAdminView']['isUserManagerSelected']['1'] = '';
        $page->data['UserSearchAdminView']['isUserManagerSelected'][$_SESSION['stippersUserSearchVars']['values']['isUserManager']] = 'selected';
        
        $page->data['UserSearchAdminView']['isAuthorizedBrowserManager'] = $_SESSION['stippersUserSearchVars']['values']['isAuthorizedBrowserManager'];
        $page->data['UserSearchAdminView']['isAuthorizedBrowserManagerSelected'][''] = "";
        $page->data['UserSearchAdminView']['isAuthorizedBrowserManagerSelected']['0'] = "";
        $page->data['UserSearchAdminView']['isAuthorizedBrowserManagerSelected']['1'] = "";
        $page->data['UserSearchAdminView']['isAuthorizedBrowserManagerSelected'][$_SESSION['stippersUserSearchVars']['values']['isAuthorizedBrowserManager']] = 'selected';
        
        if ($_SESSION['stippersUserSearchVars']['show']['isAdmin'])
            $page->data['UserSearchAdminView']['showIsAdminChecked'] = 'checked';
        else
            $page->data['UserSearchAdminView']['showIsAdminChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['isUserManager'])
            $page->data['UserSearchAdminView']['showIsUserManagerChecked'] = 'checked';
        else
            $page->data['UserSearchAdminView']['showIsUserManagerChecked'] = '';
        
        if ($_SESSION['stippersUserSearchVars']['show']['isAuthorizedBrowserManager'])
            $page->data['UserSearchAdminView']['showIsAuthorizedBrowserManagerChecked'] = 'checked';
        else
            $page->data['UserSearchAdminView']['showIsAuthorizedBrowserManagerChecked'] = '';
        
        $page->data['UserSearchAdminView']['errMsgs'] = UserSearchAdminViewValidator::initErrMsgs();
    }
    
    private static function prepUserSearchUserManagerOptionsViewData($page) {
        if ($_SESSION['stippersUserSearchVars']['options']['orderByBirthday'])
            $page->data['UserSearchUserManagerOptionsView']['orderByBirthdayChecked'] = 'checked';
        else
            $page->data['UserSearchUserManagerOptionsView']['orderByBirthdayChecked'] = '';
    }
    
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
        
        if ($_SESSION['stippersUser']->isAdmin) {
            $errMsgs = UserSearchAdminViewValidator::validate($data['UserSearchAdminView']);
            if (!empty($errMsgs)) {
                $data['UserSearchAdminView']['errMsgs'] = array_merge($data['UserSearchAdminView']['errMsgs'], $errMsgs);
                $invalid = true;
            }
        }
        
        return $invalid;
    }
        
    private static function loadDataInSession() {
        if (isset($_POST['first_name']))
            $_SESSION['stippersUserSearchVars']['values']['firstName'] = $_POST['first_name'];
        else
            $_SESSION['stippersUserSearchVars']['values']['firstName'] = '';
        if (isset($_POST['last_name']))
            $_SESSION['stippersUserSearchVars']['values']['lastName'] = $_POST['last_name'];
        else
            $_SESSION['stippersUserSearchVars']['values']['lastName'] = '';
        if (isset($_POST['email']))
            $_SESSION['stippersUserSearchVars']['values']['email'] = $_POST['email'];
        else
            $_SESSION['stippersUserSearchVars']['values']['email'] = '';
        if (isset($_POST['balance']))
            $_SESSION['stippersUserSearchVars']['values']['balance'] = $_POST['balance'];
        else
            $_SESSION['stippersUserSearchVars']['values']['balance'] = '';
        if (isset($_POST['phone']))
            $_SESSION['stippersUserSearchVars']['values']['phone'] = $_POST['phone'];
        else
            $_SESSION['stippersUserSearchVars']['values']['phone'] = '';
        if (isset($_POST['date_of_birth']))
            $_SESSION['stippersUserSearchVars']['values']['dateOfBirth'] = $_POST['date_of_birth'];
        else
            $_SESSION['stippersUserSearchVars']['values']['dateOfBirth'] = '';
        if (isset($_POST['street']))
            $_SESSION['stippersUserSearchVars']['values']['street'] = $_POST['street'];
        else
            $_SESSION['stippersUserSearchVars']['values']['street'] = '';
        if (isset($_POST['house_number']))
            $_SESSION['stippersUserSearchVars']['values']['houseNumber'] = $_POST['house_number'];
        else
            $_SESSION['stippersUserSearchVars']['values']['houseNumber'] = '';
        if (isset($_POST['city']))
            $_SESSION['stippersUserSearchVars']['values']['city'] = $_POST['city'];
        else
            $_SESSION['stippersUserSearchVars']['values']['city'] = '';
        if (isset($_POST['postalCode']))
            $_SESSION['stippersUserSearchVars']['values']['postalCode'] = $_POST['postalCode'];
        else
            $_SESSION['stippersUserSearchVars']['values']['postalCode'] = '';
        if (isset($_POST['country']))
            $_SESSION['stippersUserSearchVars']['values']['country'] = $_POST['country'];
        else
            $_SESSION['stippersUserSearchVars']['values']['country'] = '';
        if (isset($_POST['membership_year']))
            $_SESSION['stippersUserSearchVars']['values']['membershipYear'] = $_POST['membership_year'];
        else
            $_SESSION['stippersUserSearchVars']['values']['membershipYear'] = '';
        if (isset($_POST['card_number']))
            $_SESSION['stippersUserSearchVars']['values']['cardNumber'] = $_POST['card_number'];
        else
            $_SESSION['stippersUserSearchVars']['values']['cardNumber'] = '';
        if (isset($_POST['creation_time']))
            $_SESSION['stippersUserSearchVars']['values']['creationTime'] = $_POST['creation_time'];
        else
            $_SESSION['stippersUserSearchVars']['values']['creationTime'] = '';
        if (isset($_POST['is_admin']))
            $_SESSION['stippersUserSearchVars']['values']['isAdmin'] = $_POST['is_admin'];
        else
            $_SESSION['stippersUserSearchVars']['values']['isAdmin'] = '';
        if (isset($_POST['is_user_manager']))
            $_SESSION['stippersUserSearchVars']['values']['isUserManager'] = $_POST['is_user_manager'];
        else
            $_SESSION['stippersUserSearchVars']['values']['isUserManager'] = '';
        if (isset($_POST['is_authorized_browser_manager']))
            $_SESSION['stippersUserSearchVars']['values']['isAuthorizedBrowserManager'] = $_POST['is_authorized_browser_manager'];
        else
            $_SESSION['stippersUserSearchVars']['values']['isAuthorizedBrowserManager'] = '';
        
        $_SESSION['stippersUserSearchVars']['show']['firstName'] = isset($_POST['show_first_name']);
        $_SESSION['stippersUserSearchVars']['show']['lastName'] = isset($_POST['show_last_name']);
        $_SESSION['stippersUserSearchVars']['show']['email'] = isset($_POST['show_email']);
        $_SESSION['stippersUserSearchVars']['show']['balance'] = isset($_POST['show_balance']);
        $_SESSION['stippersUserSearchVars']['show']['phone'] = isset($_POST['show_phone']);
        $_SESSION['stippersUserSearchVars']['show']['dateOfBirth'] = isset($_POST['show_date_of_birth']);
        $_SESSION['stippersUserSearchVars']['show']['street'] = isset($_POST['show_street']);
        $_SESSION['stippersUserSearchVars']['show']['houseNumber'] = isset($_POST['show_house_number']);
        $_SESSION['stippersUserSearchVars']['show']['city'] = isset($_POST['show_city']);
        $_SESSION['stippersUserSearchVars']['show']['postalCode'] = isset($_POST['show_postal_code']);
        $_SESSION['stippersUserSearchVars']['show']['country'] = isset($_POST['show_country']);
        $_SESSION['stippersUserSearchVars']['show']['membershipYear'] = isset($_POST['show_membership_year']);
        $_SESSION['stippersUserSearchVars']['show']['cardNumber'] = isset($_POST['show_card_number']);
        $_SESSION['stippersUserSearchVars']['show']['creationTime'] = isset($_POST['show_creation_time']);
        $_SESSION['stippersUserSearchVars']['show']['nCheckInsPerYear'] = isset($_POST['n_check_ins_per_year']);
        $_SESSION['stippersUserSearchVars']['show']['isAdmin'] = isset($_POST['show_is_admin']);
        $_SESSION['stippersUserSearchVars']['show']['isUserManager'] = isset($_POST['show_is_user_manager']);
        $_SESSION['stippersUserSearchVars']['show']['isAuthorizedBrowserManager'] = isset($_POST['show_is_authorized_browser_manager']);
        
        $_SESSION['stippersUserSearchVars']['options']['orderByBirthday'] = isset($_POST['order_by_birthday']);
    }
    
    private static function loadSearchResults($page) {
        try {
            $users = UserDB::getSearchUsers($_SESSION['stippersUserSearchVars']['show'], $_SESSION['stippersUserSearchVars']['values'], $_SESSION['stippersUserSearchVars']['options']);
            if (count($users) == 0)
                $page->addView('userSearch/UserSearchNoResultsView');
            else {
                $page->data['UserSearchResultsView']['users'] = $users;
                $page->addView('userSearch/UserSearchResultsView');
            }
        }
        catch (Exception $ex) {
            $page->data['ErrorView']['errorMessage'] = 'Kan gebruikers niet ophalen.';
            array_push($views, 'error/ErrorView');
        }
    }
}