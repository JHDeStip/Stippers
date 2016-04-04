<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the profile page.
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

require_once __DIR__.'/../../views/profile/ProfileTopViewValidator.php';

abstract class ProfileController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Profiel';
        try {
            //Get all data of logged in user from database
            $_SESSION['Stippers']['Profile']['user'] = UserDB::getFullUserById($_SESSION['Stippers']['user']->userId);
            //Build views
            ProfileController::buildProfileTopView($page, false, false);
            $page->addView('profile/ProfileDisabledFormBottomView');
            ProfileController::buildMembershipDetailsView($page);
        }
        catch (Exception $ex) {
            $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan gegevens niet ophalen uit de database';
            $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
            $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
        }
        $page->showWithMenu();
    } 
    
    public static function post() {
        if (isset($_POST['edit'])) {
            $page = new Page();
            $page->data['title'] = 'Profiel';
            ProfileController::buildProfileTopView($page, true, false);
            $page->addView('profile/ProfileEnabledFormBottomView');
            ProfileController::buildMembershipDetailsView($page);
            $page->showWithMenu();
        }
        elseif (isset($_POST['save'])) {
            $page = new Page();
            $page->data['title'] = 'Profiel';
            $errMsgs = ProfileTopViewValidator::validate($_POST);
            if (empty($errMsgs)) {
                //If no error: create a new user from posted data and try to save it
                $newUser = ProfileController::createUserFromPost();
                try {
                    UserDB::updateUser($_SESSION['Stippers']['Profile']['user'], $newUser);
                    $page->data['SuccessMessageNoDescriptionWithLinkView']['successTitle'] = 'Gegevens succesvol bijgewerkt';
                    $page->data['SuccessMessageNoDescriptionWithLinkView']['redirectUrl'] = $_SERVER['REQUEST_URI'];
                    $page->addView('success/SuccessMessageNoDescriptionWithLinkView');
                }
                catch (UserDBException $ex) {
                    //Show correct error message for errors
                    if ($ex->getCode() == UserDBException::USEROUTOFDATE) {
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorTitle'] = 'Gegevens niet bijgewerkt';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorDescription'] = 'Iemand anders heeft je gegevens in tussentijd al gewijzigd.';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                        $page->addView('error/ErrorMessageWithDescriptionWithLinkView');
                    }
                    else {
                        ProfileController::buildProfileTopView($page, true, true);
                        
                        if($ex->getCode() == UserDBException::EMAILALREADYEXISTS)
                            $page->data['ProfileTopView']['errMsgs']['global'] = '<h2 class="error_message" id="profile_form_error_message">Dit e-mailadres is al in gebruik.</h2>';
                        else
                            $page->data['ProfileTopView']['errMsgs']['global'] = '<h2 class="error_message" id="profile_form_error_message">Kan gegevens niet bijwerken, probeer het opnieuw.</h2>';
                        $page->addView('profile/ProfileEnabledFormBottomView');
                        
                    }
                }
            }
            else {
                //If we had an error we show the views with enabled controls and take data from POST
                ProfileController::buildProfileTopView($page, true, true);
                $page->addView('profile/ProfileEnabledFormBottomView');
                $page->data['ProfileTopView']['errMsgs'] = array_merge($page->data['ProfileTopView']['errMsgs'], $errMsgs);
                ProfileController::buildMembershipDetailsView($page);
            }
            
            $page->showWithMenu();
        }
        //If cancel is clicked we show the page in it's initual states, so we call get.
        else
            ProfileController::get();
    }
    
    /**
     * Builds the view to view/change the user data.
     * 
     * @param Page $page page object to load data into
     * @param type $enabled indicates if controlls should be enabled
     * @param type $saveMode indicates if we are trying to safe
     */
    private static function buildProfileTopView($page, $enabled, $saveMode) {
        $page->data['ProfileTopView']['profile_formAction'] = $_SERVER['REQUEST_URI'];
        
        //If we're traying to save we read the data from post
        if ($saveMode) {
            $page->data['ProfileTopView']['email'] = $_POST['email'];
            $page->data['ProfileTopView']['repeatEmail'] = $_POST['repeat_email'];
            $page->data['ProfileTopView']['firstName'] = $_POST['first_name'];
            $page->data['ProfileTopView']['lastName'] = $_POST['last_name'];
            $page->data['ProfileTopView']['street'] = $_POST['street'];
            $page->data['ProfileTopView']['houseNumber'] = $_POST['house_number'];
            $page->data['ProfileTopView']['city'] = $_POST['city'];
            $page->data['ProfileTopView']['postalCode'] = $_POST['postal_code'];
            $page->data['ProfileTopView']['country'] = $_POST['country'];
            $page->data['ProfileTopView']['phone'] = $_POST['phone'];
            $page->data['ProfileTopView']['dateOfBirth'] = $_POST['date_of_birth'];
            $page->data['ProfileTopView']['balance'] = $_SESSION['Stippers']['Profile']['user']->balance/100;
        }
        //If we're not trying to save we are showing existing data
        //so we load it from the user object in session
        else {
            $page->data['ProfileTopView']['email'] = $_SESSION['Stippers']['Profile']['user']->email;
            $page->data['ProfileTopView']['repeatEmail'] = $_SESSION['Stippers']['Profile']['user']->email;
            $page->data['ProfileTopView']['firstName'] = $_SESSION['Stippers']['Profile']['user']->firstName;
            $page->data['ProfileTopView']['lastName'] = $_SESSION['Stippers']['Profile']['user']->lastName;
            $page->data['ProfileTopView']['street'] = $_SESSION['Stippers']['Profile']['user']->street;
            $page->data['ProfileTopView']['houseNumber'] = $_SESSION['Stippers']['Profile']['user']->houseNumber;
            $page->data['ProfileTopView']['city'] = $_SESSION['Stippers']['Profile']['user']->city;
            $page->data['ProfileTopView']['postalCode'] = $_SESSION['Stippers']['Profile']['user']->postalCode;
            $page->data['ProfileTopView']['country'] = $_SESSION['Stippers']['Profile']['user']->country;
            $page->data['ProfileTopView']['phone'] = $_SESSION['Stippers']['Profile']['user']->phone;
            $page->data['ProfileTopView']['dateOfBirth'] = $_SESSION['Stippers']['Profile']['user']->dateOfBirth;
            $page->data['ProfileTopView']['balance'] = $_SESSION['Stippers']['Profile']['user']->balance/100;
        }
            
        if ($enabled)
            $page->data['ProfileTopView']['disabled'] = '';
        else
            $page->data['ProfileTopView']['disabled'] = 'disabled';
        
        $page->addView('profile/ProfileTopView');
        $page->data['ProfileTopView']['errMsgs'] = ProfileTopViewValidator::initErrMsgs();
    }
    
        
    /**
     * Builds the view for membership details.
     * 
     * @param Page $page page object to load data into
     */
    private static function buildMembershipDetailsView($page) {
        $page->addView('profile/ProfileMyMembershipCardView');
        try {
            $page->data['MembershipDetailsView']['membershipYearDetails'] = MembershipDB::getUserMembershipDetailsByUserId($_SESSION['Stippers']['Profile']['user']->userId);
            $page->data['MembershipDetailsView']['totalCheckIns'] = CheckInDB::getTotalCheckInsByUserId($_SESSION['Stippers']['Profile']['user']->userId);
            $page->addView('membershipDetails/MembershipDetailsView');
        }
        catch (Exception $ex) {
            $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan lidkaartgegevens niet ophalen uit de database.';
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
        $user->passwordHash = $_SESSION['Stippers']['Profile']['user']->passwordHash;
        $user->phone = $_POST['phone'];
        $user->dateOfBirth = $_POST['date_of_birth'];
        $user->street = $_POST['street'];
        $user->houseNumber = $_POST['house_number'];
        $user->city = $_POST['city'];
        $user->postalCode = $_POST['postal_code'];
        $user->country = $_POST['country'];
        $user->balance = $_SESSION['Stippers']['Profile']['user']->balance;
        $user->creationTime = $_SESSION['Stippers']['Profile']['user']->creationTime;
        $user->isAdmin = $_SESSION['Stippers']['Profile']['user']->isAdmin;
        $user->isUserManager = $_SESSION['Stippers']['Profile']['user']->isUserManager;
        $user->isBrowserManager = $_SESSION['Stippers']['Profile']['user']->isBrowserManager;
        
        return $user;
    }
}