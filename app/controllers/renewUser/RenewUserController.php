<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the renew user page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../helperClasses/random/Random.php';
require_once __DIR__.'/../../config/SecurityConfig.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../views/addRenewUser/UserDataFormTopViewValidator.php';
require_once __DIR__.'/../../views/addRenewUser/UserDataFormPasswordViewValidator.php';
require_once __DIR__.'/../../views/addRenewUser/UserDataFormMiddleViewValidator.php';

abstract class RenewUserController implements IController {
    
    public static function get() {
        if (!isset($_GET['user'])) {
            //No user id given so redirect to the manageuser
            //page to search a user
            header('Location: renewusersearch', TRUE, 303);
        }
        else {
            $page = new Page();
            $page->data['title'] = 'Gebruiker hernieuwen';
            try {
                $_SESSION['Stippers']['RenewUser']['user'] = UserDB::getFullUserById($_GET['user']);
                RenewUserController::buildRenewUserPage($page);
            }
            catch(Exception $ex) {
                $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kon gegevens van gebruiker niet ophalen';
                $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
            }
            $page->showBasic();
        }
    }
    
    public static function post() {
        if (isset($_POST['create'])) {
            $page = new Page();
            $page->data['title'] = 'Gebruiker hernieuwen';
            
            //Validate input
            $formTopViewErrMsgs = UserDataFormTopViewValidator::validate($_POST);
            $formMiddleViewErrMsgs = UserDataFormMiddleViewValidator::validate($_POST);
            
            //No error means we create a user and password salt
            if (empty($formTopViewErrMsgs) && empty($formMiddleViewErrMsgs)) {
                $newUser = new User();
                $newUser->userId = $_SESSION['Stippers']['RenewUser']['user']->userId;
                $newUser->email = $_POST['email'];
                $newUser->passwordHash = $_SESSION['Stippers']['RenewUser']['user']->passwordHash;
                $newUser->firstName = ucwords($_POST['first_name']);
                $newUser->lastName = ucwords($_POST['last_name']);
                $newUser->street = ucwords($_POST['street']);
                $newUser->houseNumber = $_POST['house_number'];
                $newUser->city = ucwords($_POST['city']);
                $newUser->postalCode = $_POST['postal_code'];
                $newUser->country = ucwords($_POST['country']);
                $newUser->phone = $_POST['phone'];
                $newUser->dateOfBirth = $_POST['date_of_birth'];
                $newUser->balance = $_SESSION['Stippers']['RenewUser']['user']->balance;
                $newUser->isAdmin = $_SESSION['Stippers']['RenewUser']['user']->isAdmin;
                $newUser->isHintManager = $_SESSION['Stippers']['RenewUser']['user']->isHintManager;
                $newUser->isUserManager = $_SESSION['Stippers']['RenewUser']['user']->isUserManager;
                $newUser->isAuthorizedBrowserManager = $_SESSION['Stippers']['RenewUser']['user']->isAuthorizedBrowserManager;
                $newUser->creationTime = $_SESSION['Stippers']['RenewUser']['user']->creationTime;
                
                //Renew the user
                try {
                    UserDB::renewMembership($_SESSION['Stippers']['RenewUser']['user'], $newUser, $_POST['card_number']);
                    $page->addView('renewUser/SuccessfullyRenewedView');
                }
                catch(UserDBException $ex) {
                    if ($ex->getCode() == UserDBException::USERALREADYMEMBER) {
                        $page->addView('renewUser/UserAlreadyMemberView');
                    }
                    elseif ($ex->getCode() == UserDBException::USEROUTOFDATE) {
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorTitle'] = 'Gebruiker niet hernieuwd';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorDescription'] = 'Iemand anders heeft de gebruiker in tussentijd al gewijzigd.';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                        $page->addView('error/ErrorMessageWithDescriptionWithLinkView');
                    }
                    else {
                        RenewUserController::buildRenewUserPage($page);
                        if ($ex->getCode() == UserDBException::EMAILALREADYEXISTS)
                            $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_user_form_error_message">Dit e-mailadres is al in gebruik.</h2>';
                        elseif ($ex->getCode() == UserDBException::CARDALREADYUSED)
                            $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_user_form_error_message">Dit kaartnummer is al in gebruik.</h2>';
                        else
                            $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_user_form_error_message">Kan gebruiker niet hernieuwen, probeer het opnieuw.</h2>';
                    }
                }
                catch(Exception $ex) {
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Gebruiker niet hernieuwd';
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                    $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
                }
            }
            else {
                RenewUserController::buildRenewUserPage($page);
                $page->data['UserDataFormTopView']['errMsgs'] = array_merge($page->data['UserDataFormTopView']['errMsgs'], $formTopViewErrMsgs);
                $page->data['UserDataFormPasswordView']['errMsgs'] = array_merge($page->data['UserDataFormPasswordView']['errMsgs'], $formPasswordViewErrMsgs);
                $page->data['UserDataFormMiddleView']['errMsgs'] = array_merge($page->data['UserDataFormMiddleView']['errMsgs'], $formMiddleViewErrMsgs);
            }
            $page->showBasic();
        }
        else
            header('Location: renewusersearch', TRUE, 303);
    }
    
    /**
     * Builds the page for the user input.
     * 
     * @param Page $page page to add the views to
     */
    private static function buildRenewUserPage(Page $page) {
        $page->addView('renewUser/RenewUserTopView');
        
        $page->data['UserDataFormTopView']['user_data_formAction'] = $_SERVER['REQUEST_URI'];
        if (isset($_POST['card_number']))
            $page->data['UserDataFormTopView']['cardNumber'] = $_POST['card_number'];
        else
            $page->data['UserDataFormTopView']['cardNumber'] = '';
        if (isset($_POST['email']))
            $page->data['UserDataFormTopView']['email'] = $_POST['email'];
        else
            $page->data['UserDataFormTopView']['email'] = $_SESSION['Stippers']['RenewUser']['user']->email;
        if (isset($_POST['repeat_email']))
            $page->data['UserDataFormTopView']['repeatEmail'] = $_POST['repeat_email'];
        else
            $page->data['UserDataFormTopView']['repeatEmail'] = $_SESSION['Stippers']['RenewUser']['user']->email;
        $page->data['UserDataFormTopView']['errMsgs'] = UserDataFormTopViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormTopView');
        
        if (isset($_POST['first_name']))
            $page->data['UserDataFormMiddleView']['firstName'] = $_POST['first_name'];
        else
            $page->data['UserDataFormMiddleView']['firstName'] = $_SESSION['Stippers']['RenewUser']['user']->firstName;
        if (isset($_POST['last_name']))
            $page->data['UserDataFormMiddleView']['lastName'] = $_POST['last_name'];
        else
            $page->data['UserDataFormMiddleView']['lastName'] = $_SESSION['Stippers']['RenewUser']['user']->lastName;
        if (isset($_POST['street']))
            $page->data['UserDataFormMiddleView']['street'] = $_POST['street'];
        else
            $page->data['UserDataFormMiddleView']['street'] = $_SESSION['Stippers']['RenewUser']['user']->street;
        if (isset($_POST['house_number']))
            $page->data['UserDataFormMiddleView']['houseNumber'] = $_POST['house_number'];
        else
            $page->data['UserDataFormMiddleView']['houseNumber'] = $_SESSION['Stippers']['RenewUser']['user']->houseNumber;
        if (isset($_POST['city']))
            $page->data['UserDataFormMiddleView']['city'] = $_POST['city'];
        else
            $page->data['UserDataFormMiddleView']['city'] = $_SESSION['Stippers']['RenewUser']['user']->city;
        if (isset($_POST['postal_code']))
            $page->data['UserDataFormMiddleView']['postalCode'] = $_POST['postal_code'];
        else
            $page->data['UserDataFormMiddleView']['postalCode'] = $_SESSION['Stippers']['RenewUser']['user']->postalCode;
        if (isset($_POST['country']))
            $page->data['UserDataFormMiddleView']['country'] = $_POST['country'];
        else
            $page->data['UserDataFormMiddleView']['country'] = $_SESSION['Stippers']['RenewUser']['user']->country;
        if (isset($_POST['phone']))
            $page->data['UserDataFormMiddleView']['phone'] = $_POST['phone'];
        else
            $page->data['UserDataFormMiddleView']['phone'] = $_SESSION['Stippers']['RenewUser']['user']->phone;
        if (isset($_POST['date_of_birth']))
            $page->data['UserDataFormMiddleView']['dateOfBirth'] = $_POST['date_of_birth'];
        else
            $page->data['UserDataFormMiddleView']['dateOfBirth'] = $_SESSION['Stippers']['RenewUser']['user']->dateOfBirth;
        $page->data['UserDataFormMiddleView']['errMsgs'] = UserDataFormMiddleViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormMiddleView');
        
        $page->addView('renewUser/UserDataFormBottomView');
    }
}