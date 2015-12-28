<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the add user page.
 */

require_once __DIR__.'/../../IController.php';
require_once __DIR__.'/../../../helperClasses/Page.php';

require_once __DIR__.'/../../../helperClasses/random/Random.php';

require_once __DIR__.'/../../../models/user/User.php';
require_once __DIR__.'/../../../models/user/UserDB.php';
require_once __DIR__.'/../../../models/user/UserDBException.php';

require_once __DIR__.'/../../../views/addRenewUser/UserDataFormTopViewValidator.php';
require_once __DIR__.'/../../../views/addRenewUser/UserDataFormPasswordViewValidator.php';
require_once __DIR__.'/../../../views/addRenewUser/UserDataFormMiddleViewValidator.php';

abstract class AddUserController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Gebruiker toevoegen';
        AddUserController::buildAddUserPage($page, false);
        $page->showBasic();
    }
    
    public static function post() {
        $page = new Page();
        $page->data['title'] = 'Gebruiker toevoegen';
        
        //Validate input
        $formTopViewErrMsgs = UserDataFormTopViewValidator::validate($_POST);
        $formPasswordViewErrMsgs = UserDataFormPasswordViewValidator::validate($_POST);
        $formMiddleViewErrMsgs = UserDataFormMiddleViewValidator::validate($_POST);
        
        //No error means we create a user and password salt
        if (empty($formTopViewErrMsgs) && empty($formPasswordViewErrMsgs) && empty($formMiddleViewErrMsgs)) {
            $passwordSalt = Random::getGuid();
            $user = new User();
            $user->email = $_POST['email'];
            $user->firstName = ucwords($_POST['first_name']);
            $user->lastName = ucwords($_POST['last_name']);
            $user->passwordHash = hash_pbkdf2("sha256", $_POST['password'], $passwordSalt, SecurityConfig::NPASSWORDHASHITERATIONS);
            $user->street = ucwords($_POST['street']);
            $user->houseNumber = $_POST['house_number'];
            $user->city = ucwords($_POST['city']);
            $user->postalCode = $_POST['postal_code'];
            $user->country = ucwords($_POST['country']);
            $user->phone = $_POST['phone'];
            $user->dateOfBirth = $_POST['date_of_birth'];
            
            //Add the user
            try {
                UserDB::addUser($user, $passwordSalt, $_POST['card_number']);
                $page->addView('addRenewUser/addUser/SuccessfullyAddedView');
            }
            catch(UserDBException $ex) {
                AddUserController::buildAddUserPage($page, true);
                if ($ex->getCode() == UserDBException::EMAILALREADYEXISTS)
                    $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_user_form_error_message">Dit e-mailadres is al in gebruik.</h2>';
                elseif ($ex->getCode() == UserDBException::CARDALREADYUSED)
                    $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_user_form_error_message">Dit kaartnummer is al in gebruik.</h2>';
                else
                    $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_user_form_error_message">Kan gebruiker niet toevoegen, probeer het opnieuw.</h2>';
            }
            catch(Exception $ex) {
                AddUserController::buildAddUserPage($page, true);
                $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_user_form_error_message">Kan gebruiker niet toevoegen, probeer het opnieuw.</h2>';
            }
        }
        else {
            AddUserController::buildAddUserPage($page, true);
            $page->data['UserDataFormTopView']['errMsgs'] = array_merge($page->data['UserDataFormTopView']['errMsgs'], $formTopViewErrMsgs);
            $page->data['UserDataFormPasswordView']['errMsgs'] = array_merge($page->data['UserDataFormPasswordView']['errMsgs'], $formPasswordViewErrMsgs);
            $page->data['UserDataFormMiddleView']['errMsgs'] = array_merge($page->data['UserDataFormMiddleView']['errMsgs'], $formMiddleViewErrMsgs);
        }
        $page->showBasic();
    }
    
    /**
     * Builds the page for the user input.
     * 
     * @param Page $page page to add the views to
     */
    private static function buildAddUserPage(Page $page, $saveMode) {
        $page->addView('addRenewUser/addUser/AddUserTopView');
        
        $page->data['UserDataFormTopView']['user_data_formAction'] = $_SERVER['REQUEST_URI'];
        
        if ($saveMode) {
            $page->data['UserDataFormTopView']['cardNumber'] = $_POST['card_number'];
            $page->data['UserDataFormTopView']['email'] = $_POST['email'];
            $page->data['UserDataFormTopView']['repeatEmail'] = $_POST['repeat_email'];
            $page->data['UserDataFormMiddleView']['firstName'] = $_POST['first_name'];
            $page->data['UserDataFormMiddleView']['lastName'] = $_POST['last_name'];
            $page->data['UserDataFormMiddleView']['street'] = $_POST['street'];
            $page->data['UserDataFormMiddleView']['houseNumber'] = $_POST['house_number'];
            $page->data['UserDataFormMiddleView']['city'] = $_POST['city'];
            $page->data['UserDataFormMiddleView']['postalCode'] = $_POST['postal_code'];
            $page->data['UserDataFormMiddleView']['country'] = $_POST['country'];
            $page->data['UserDataFormMiddleView']['phone'] = $_POST['phone'];
            $page->data['UserDataFormMiddleView']['dateOfBirth'] = $_POST['date_of_birth'];
        }
        else {
            $page->data['UserDataFormTopView']['cardNumber'] = '';
            $page->data['UserDataFormTopView']['email'] = '';
            $page->data['UserDataFormTopView']['repeatEmail'] = '';
            
        
        
            $page->data['UserDataFormMiddleView']['firstName'] = '';
            $page->data['UserDataFormMiddleView']['lastName'] = '';
            $page->data['UserDataFormMiddleView']['street'] = '';
            $page->data['UserDataFormMiddleView']['houseNumber'] = '';
            $page->data['UserDataFormMiddleView']['city'] = '';
            $page->data['UserDataFormMiddleView']['postalCode'] = '';
            $page->data['UserDataFormMiddleView']['country'] = '';
            $page->data['UserDataFormMiddleView']['phone'] = '';
            $page->data['UserDataFormMiddleView']['dateOfBirth'] = '';
        }
        
        $page->data['UserDataFormTopView']['errMsgs'] = UserDataFormTopViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormTopView');
        
        $page->data['UserDataFormPasswordView']['errMsgs'] = UserDataFormPasswordViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormPasswordView');
        $page->data['UserDataFormMiddleView']['errMsgs'] = UserDataFormMiddleViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormMiddleView');
        
        $page->addView('addRenewUser/addUser/UserDataFormBottomView');
    }
}