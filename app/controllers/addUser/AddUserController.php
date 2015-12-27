<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the add user page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../views/addRenewUser/UserDataFormTopViewValidator.php';
require_once __DIR__.'/../../views/addRenewUser/UserDataFormPasswordViewValidator.php';
require_once __DIR__.'/../../views/addRenewUser/UserDataFormMiddleViewValidator.php';

abstract class AddUserController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Gebruiker toevoegen';
        AddUserController::buildAddUserPage($page);
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
                $page->addView('addUser/SuccessfullyAddedView');
            }
            catch(UserDBException $ex) {
                AddUserController::buildAddUserPage($page);
                if ($ex->getCode() == UserDBException::EMAILALREADYEXISTS)
                    $page->data['UserDataFormTopView']['errMsgs']['email'] = '<label class="form_label_error" for="email" id="form_label_error_email">Dit e-mailadres is al in gebruik.</label>';
                elseif ($ex->getCode() == UserDBException::CARDALREADYUSED)
                    $page->data['UserDataFormTopView']['errMsgs']['cardNumber'] = '<label class="form_label_error" for="card_number" id="form_label_error_card_number">Dit kaartnummer is al in gebruik.</label>';
                else
                    $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_user_form_error_message">Kan gebruiker niet toevoegen, probeer het opnieuw.</h2>';
            }
            catch(Exception $ex) {
                AddUserController::buildAddUserPage($page);
                $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_user_form_error_message">Kan gebruiker niet toevoegen, probeer het opnieuw.</h2>';
            }
        }
        else {
            AddUserController::buildAddUserPage($page);
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
    private static function buildAddUserPage(Page $page) {
        $page->addView('addUser/AddUserTopView');
        
        $page->data['UserDataFormTopView']['user_data_formAction'] = $_SERVER['REQUEST_URI'];
        if (isset($_POST['card_number']))
            $page->data['UserDataFormTopView']['cardNumber'] = $_POST['card_number'];
        else
            $page->data['UserDataFormTopView']['cardNumber'] = '';
        if (isset($_POST['email']))
            $page->data['UserDataFormTopView']['email'] = $_POST['email'];
        else
            $page->data['UserDataFormTopView']['email'] = '';
        if (isset($_POST['repeat_email']))
            $page->data['UserDataFormTopView']['repeatEmail'] = $_POST['repeat_email'];
        else
            $page->data['UserDataFormTopView']['repeatEmail'] = '';
        $page->data['UserDataFormTopView']['errMsgs'] = UserDataFormTopViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormTopView');
        
        $page->data['UserDataFormPasswordView']['errMsgs'] = UserDataFormPasswordViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormPasswordView');
        
        if (isset($_POST['first_name']))
            $page->data['UserDataFormMiddleView']['firstName'] = $_POST['first_name'];
        else
            $page->data['UserDataFormMiddleView']['firstName'] = '';
        if (isset($_POST['last_name']))
            $page->data['UserDataFormMiddleView']['lastName'] = $_POST['last_name'];
        else
            $page->data['UserDataFormMiddleView']['lastName'] = '';
        if (isset($_POST['street']))
            $page->data['UserDataFormMiddleView']['street'] = $_POST['street'];
        else
            $page->data['UserDataFormMiddleView']['street'] = '';
        if (isset($_POST['house_number']))
            $page->data['UserDataFormMiddleView']['houseNumber'] = $_POST['house_number'];
        else
            $page->data['UserDataFormMiddleView']['houseNumber'] = '';
        if (isset($_POST['city']))
            $page->data['UserDataFormMiddleView']['city'] = $_POST['city'];
        else
            $page->data['UserDataFormMiddleView']['city'] = '';
        if (isset($_POST['postal_code']))
            $page->data['UserDataFormMiddleView']['postalCode'] = $_POST['postal_code'];
        else
            $page->data['UserDataFormMiddleView']['postalCode'] = '';
        if (isset($_POST['country']))
            $page->data['UserDataFormMiddleView']['country'] = $_POST['country'];
        else
            $page->data['UserDataFormMiddleView']['country'] = '';
        if (isset($_POST['phone']))
            $page->data['UserDataFormMiddleView']['phone'] = $_POST['phone'];
        else
            $page->data['UserDataFormMiddleView']['phone'] = '';
        if (isset($_POST['date_of_birth']))
            $page->data['UserDataFormMiddleView']['dateOfBirth'] = $_POST['date_of_birth'];
        else
            $page->data['UserDataFormMiddleView']['dateOfBirth'] = '';
        $page->data['UserDataFormMiddleView']['errMsgs'] = UserDataFormMiddleViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormMiddleView');
        
        $page->addView('addUser/UserDataFormBottomView');
    }
}