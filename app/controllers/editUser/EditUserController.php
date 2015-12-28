<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the edit user page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../models/membership/MembershipDB.php';
require_once __DIR__.'/../../models/membership/MembershipDBException.php';

require_once __DIR__.'/../../models/checkIn/CheckInDB.php';
require_once __DIR__.'/../../models/checkIn/CheckInDBException.php';

require_once __DIR__.'/../../views/EditUser/EditUserTopViewValidator.php';

abstract class EditUserController implements IController {
    
    public static function get() {
        if (!isset($_GET['user'])) {
            //No user id given so redirect to the manageuser
            //page to search a user
            header('Location: manageuser', true, 303);
        }
        else {
            $page = new Page();
            $page->data['title'] = 'Gebruiker bewerken';
            try {
                //Get user who's ID is given in GET from database
                $_SESSION['Stippers']['EditUser']['user'] = UserDB::getFullUserById($_GET['user']);
                //Build views
                EditUserController::buildEditUserTopView($page, false, false);
                if ($_SESSION['Stippers']['user']->isAdmin)
                    EditUserController::buildEditUserAdminView($page, false, false);
                $page->addView('editUser/EditUserDisabledFormBottomView');
                EditUserController::buildEditUserMembershipDetailsView($page);
            }
            catch (UserDBException $ex) {
                if ($ex->getCode() == UserDBException::NOUSERFORID)
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Er is geen gebruiker met deze id';
                else
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan gegevens niet ophalen uit de database';
                
                $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
            }
            catch (Exception $ex) {
                $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan gegevens niet ophalen uit de database';
                $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
            }
            $page->showBasic();
        }
    } 
    
    public static function post() {
        //Redirect to manageuser page if back to results button was clicked
        if (isset($_POST['back_to_search_results']))
            header('Location: manageuser', true, 303);
        //Stop editing if cancel was clocked, this should do the same as GET, so we just call get.
        elseif (isset($_POST['cancel']))
            EditUserController::get();
        //If we click edit we show all views with enabled controls.
        elseif (isset($_POST['edit'])) {
            $page = new Page();
            $page->data['title'] = 'Gebruiker bewerken';
            EditUserController::buildEditUserTopView($page, true, false);
            if ($_SESSION['Stippers']['user']->isAdmin)
                EditUserController::buildEditUserAdminView($page, true, false);
            $page->addView('editUser/EditUserEnabledFormBottomView');
            EditUserController::buildEditUserMembershipDetailsView($page);
            $page->showBasic();
        }
        //If the save button was clicked
        else {
            $page = new Page();
            $page->data['title'] = 'Gebruiker bewerken';
            $errMsgs = EditUserTopViewValidator::validate($_POST);
            if (empty($errMsgs)) {
                //If no error: create a new user from posted data and try to save it
                $newUser = EditUserController::createUserFromPost();
                try {
                    UserDB::updateUser($_SESSION['Stippers']['EditUser']['user'], $newUser);
                    $page->data['SuccessMessageNoDescriptionWithLinkView']['successTitle'] = 'Gebruiker succesvol bijgewerkt';
                    $page->data['SuccessMessageNoDescriptionWithLinkView']['redirectUrl'] = $_SERVER['REQUEST_URI'];
                    $page->addView('success/SuccessMessageNoDescriptionWithLinkView');
                }
                catch (Exception $ex) {
                    //Show correct error message for errors
                    if ($ex->getCode() == UserDBException::USEROUTOFDATE) {
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorTitle'] = 'Gebruiker niet bijgewerkt';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorDescription'] = 'Iemand anders heeft de gebruiker in tussentijd al gewijzigd.';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                        $page->addView('error/ErrorMessageWithDescriptionWithLinkView');
                    }
                    elseif($ex->getCode() == UserDBException::EMAILALREADYEXISTS) {
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorTitle'] = 'Gebruiker niet bijgewerkt';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorDescription'] = 'Dit e-mailadres is al in gebruik.';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                        $page->addView('error/ErrorMessageWithDescriptionWithLinkView');
                    }
                    else {
                        $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan gebruiker niet bijwerken';
                        $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                        $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
                    }
                }
            }
            else {
                //If we had an error we show the views with enabled controls and take data from POST
                EditUserController::buildEditUserTopView($page, true, true);
                if ($_SESSION['Stippers']['user']->isAdmin)
                    EditUserController::buildEditUserAdminView($page, true, true);
                $page->addView('editUser/EditUserEnabledFormBottomView');
                $page->data['EditUserTopView']['errMsgs'] = array_merge($page->data['EditUserTopView']['errMsgs'], $errMsgs);
                EditUserController::buildEditUserMembershipDetailsView($page);
            }
            $page->showBasic();
        }
    }
    
    /**
     * Builds the view to view/change the user data
     * that are not permission related.
     * 
     * @param Page $page page object to load data into
     * @param type $enabled indicates if controlls should be enabled
     * @param type $saveMode indicates if we are trying to safe
     */
    private static function buildEditUserTopView($page, $enabled, $saveMode) {
        $page->data['EditUserTopView']['edit_user_formAction'] = $_SERVER['REQUEST_URI'];
        
        //If we're traying to save we read the data from post
        if ($saveMode) {
            $page->data['EditUserTopView']['email'] = $_POST['email'];
            $page->data['EditUserTopView']['repeatEmail'] = $_POST['repeat_email'];
            $page->data['EditUserTopView']['firstName'] = $_POST['first_name'];
            $page->data['EditUserTopView']['lastName'] = $_POST['last_name'];
            $page->data['EditUserTopView']['street'] = $_POST['street'];
            $page->data['EditUserTopView']['houseNumber'] = $_POST['house_number'];
            $page->data['EditUserTopView']['city'] = $_POST['city'];
            $page->data['EditUserTopView']['postalCode'] = $_POST['postal_code'];
            $page->data['EditUserTopView']['country'] = $_POST['country'];
            $page->data['EditUserTopView']['phone'] = $_POST['phone'];
            $page->data['EditUserTopView']['dateOfBirth'] = $_POST['date_of_birth'];
            $page->data['EditUserTopView']['creationTime'] = $_SESSION['Stippers']['EditUser']['user']->creationTime;
            $page->data['EditUserTopView']['balance'] = $_SESSION['Stippers']['EditUser']['user']->balance;
        }
        //If we're not trying to save we are showing existing data
        //so we load it from the user object in session
        else {
            $page->data['EditUserTopView']['email'] = $_SESSION['Stippers']['EditUser']['user']->email;
            $page->data['EditUserTopView']['repeatEmail'] = $_SESSION['Stippers']['EditUser']['user']->email;
            $page->data['EditUserTopView']['firstName'] = $_SESSION['Stippers']['EditUser']['user']->firstName;
            $page->data['EditUserTopView']['lastName'] = $_SESSION['Stippers']['EditUser']['user']->lastName;
            $page->data['EditUserTopView']['street'] = $_SESSION['Stippers']['EditUser']['user']->street;
            $page->data['EditUserTopView']['houseNumber'] = $_SESSION['Stippers']['EditUser']['user']->houseNumber;
            $page->data['EditUserTopView']['city'] = $_SESSION['Stippers']['EditUser']['user']->city;
            $page->data['EditUserTopView']['postalCode'] = $_SESSION['Stippers']['EditUser']['user']->postalCode;
            $page->data['EditUserTopView']['country'] = $_SESSION['Stippers']['EditUser']['user']->country;
            $page->data['EditUserTopView']['phone'] = $_SESSION['Stippers']['EditUser']['user']->phone;
            $page->data['EditUserTopView']['dateOfBirth'] = $_SESSION['Stippers']['EditUser']['user']->dateOfBirth;
            $page->data['EditUserTopView']['creationTime'] = $_SESSION['Stippers']['EditUser']['user']->creationTime;
            $page->data['EditUserTopView']['balance'] = $_SESSION['Stippers']['EditUser']['user']->balance;
        }
            
        if ($enabled)
            $page->data['EditUserTopView']['disabled'] = '';
        else
            $page->data['EditUserTopView']['disabled'] = 'disabled';
        
        $page->addView('editUser/EditUserTopView');
        $page->data['EditUserTopView']['errMsgs'] = EditUserTopViewValidator::initErrMsgs();
    }
    
    /**
     * Builds the view to view/change the permission data.
     * 
     * @param Page $page page object to load data into
     * @param type $enabled indicates if controlls should be enabled
     * @param type $saveMode indicates if we are trying to safe
     */
    private static function buildEditUserAdminView($page, $enabled, $saveMode) {
        //If we're traying to save we read the data from post
        if ($saveMode) {
            $page->data['EditUserAdminView']['isAdminChecked'] = (isset($_POST['is_admin_checked']) ? 'checked' : '');
            $page->data['EditUserAdminView']['isUserManagerChecked'] = (isset($_POST['is_user_manager_checked']) ? 'checked' : '');
            $page->data['EditUserAdminView']['isBrowserManagerChecked'] = (isset($_POST['is_browser_manager_checked']) ? 'checked' : '');
        }
        //If we're not trying to save we are showing existing data
        //so we load it from the user object in session
        else {
            $page->data['EditUserAdminView']['isAdminChecked'] = ($_SESSION['Stippers']['EditUser']['user']->isAdmin ? 'checked' : '');
            $page->data['EditUserAdminView']['isUserManagerChecked'] = ($_SESSION['Stippers']['EditUser']['user']->isUserManager ? 'checked' : '');
            $page->data['EditUserAdminView']['isBrowserManagerChecked'] = ($_SESSION['Stippers']['EditUser']['user']->isBrowserManager ? 'checked' : '');
        }
        
        if ($enabled)
            $page->data['EditUserAdminView']['disabled'] = '';
        else
            $page->data['EditUserAdminView']['disabled'] = 'disabled';
        
        $page->addView('editUser/EditUserAdminView');
    }
        
    /**
     * Builds the view for membership details.
     * 
     * @param Page $page page object to load data into
     */
    private static function buildEditUserMembershipDetailsView($page) {
        try {
            $page->data['EditUserMembershipDetailsView']['membershipYearDetails'] = MembershipDB::getUserMembershipDetailsByUserId($_SESSION['Stippers']['EditUser']['user']->userId);
            $page->data['EditUserMembershipDetailsView']['totalCheckIns'] = CheckInDB::getTotalCheckInsByUserId($_SESSION['Stippers']['EditUser']['user']->userId);
            $page->addView('editUser/EditUserMemberShipDetailsView');
        }
        catch (Exception $ex) {
            $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan gegevens niet ophalen uit de database.';
            $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
            $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
        }
    }
    
    /**
     * Creates a user from data in POST.
     * 
     * @return User newly created user object
     */
    private static function createUserFromPost() {
        $user = new User();
        $user->email = $_POST['email'];
        $user->firstName = $_POST['first_name'];
        $user->lastName = $_POST['last_name'];
        $user->passwordHash = $_SESSION['Stippers']['EditUser']['user']->passwordHash;
        $user->balance = $_SESSION['Stippers']['EditUser']['user']->balance;
        $user->phone = $_POST['phone'];
        $user->dateOfBirth = $_POST['date_of_birth'];
        $user->street = $_POST['street'];
        $user->houseNumber = $_POST['house_number'];
        $user->city = $_POST['city'];
        $user->postalCode = $_POST['postal_code'];
        $user->country = $_POST['country'];
        $user->creationTime = $_SESSION['Stippers']['EditUser']['user']->creationTime;
        
        if ($_SESSION['Stippers']['user']->isAdmin) {
            $user->isAdmin = isset($_POST['is_admin']);
            $user->isUserManager = isset($_POST['is_user_manager']);
            $user->isBrowserManager = isset($_POST['is_browser_manager']);
        }
        //If you're not an admin, don't take permission data from post
        //but keep the data that was already in the session
        else {
            $user->isAdmin = $_SESSION['Stippers']['EditUser']['user']->isAdmin;
            $user->isUserManager = $_SESSION['Stippers']['EditUser']['user']->isUserManager;
            $user->isBrowserManager = $_SESSION['Stippers']['EditUser']['user']->isBrowserManager;
        }
        
        return $user;
    }
}