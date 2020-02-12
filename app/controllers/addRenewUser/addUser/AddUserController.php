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

require_once __DIR__.'/../../../helperClasses/email/Email.php';
require_once __DIR__.'/../../../helperClasses/email/EmailException.php';

require_once __DIR__.'/../../../config/EmailConfig.php';

require_once __DIR__.'/../../../config/AddOrRenewUserConfig.php';

require_once __DIR__.'/../../../models/user/User.php';
require_once __DIR__.'/../../../models/user/UserDB.php';
require_once __DIR__.'/../../../models/user/UserDBException.php';

require_once __DIR__.'/../../../models/moneyTransaction/MoneyTransaction.php';
require_once __DIR__.'/../../../models/moneyTransaction/MoneyTransactionDB.php';
require_once __DIR__.'/../../../models/moneyTransaction/MoneyTransactionDBException.php';

require_once __DIR__.'/../../../views/addRenewUser/UserDataFormTopViewValidator.php';
require_once __DIR__.'/../../../views/addRenewUser/UserDataFormPasswordViewValidator.php';
require_once __DIR__.'/../../../views/addRenewUser/UserDataFormMiddleViewValidator.php';

abstract class AddUserController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Gebruiker toevoegen';
        AddUserController::buildAddUserPage($page, false);
        $page->showWithMenu();
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
            $user->passwordHash = hash_pbkdf2("sha256", $_POST['password'], $passwordSalt, SecurityConfig::N_PASSWORD_HASH_ITERATIONS);
            $user->street = ucwords($_POST['street']);
            $user->houseNumber = $_POST['house_number'];
            $user->city = ucwords($_POST['city']);
            $user->postalCode = $_POST['postal_code'];
            $user->dateOfBirth = $_POST['date_of_birth'];
            
            //Add the user
            try {
                $userId = UserDB::addUser($user, $passwordSalt, $_POST['card_number']);

                $page->addView('addRenewUser/addUser/SuccessfullyAddedView');
                //Send welcome mail
                try {
                    
                    $failedEmails = Email::sendEmails('WelcomeNewMember.html', 'JH DE Stip - Welkom', EmailConfig::FROM_ADDRESS, [$user], null);
                    //If failedEmails is not empty the mail was not sent
                    if (!empty($failedEmails)) {
                        $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] = 'Kan welkomstmail niet verzenden.';
                        $page->addView('error/ErrorMessageNoDescriptionNoLinkView');
                    }
                }
                catch (Exception $ex) {
                    $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] = 'Kan welkomstmail niet verzenden.';
                    $page->addView('error/ErrorMessageNoDescriptionNoLinkView');
                }
                
                //Add money to user's card
                try {
                    $addedUser = UserDB::getFullUserById($userId);
                    $executingBrowserName = BrowserDB::getBrowserById($_SESSION['Stippers']['browser']->browserId)->name;
                    $trans = new MoneyTransaction(null, $addedUser->userId, 0, AddOrRenewUserConfig::NEW_OR_RENEWED_USER_BONUS, 0, 0, true, null, $executingBrowserName, null);
                    MoneyTransactionDB::addTransaction($addedUser, $trans);
                }
                catch (Exception $ex) {
                    if (isset($page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle']))
                        $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] .= ' Kan het saldo van het account niet verhogen, probeer dit handmatig te doen.';
                    else
                        $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] = 'Kan het saldo van het account niet verhogen, probeer dit handmatig te doen.';
                    $page->addView('error/ErrorMessageNoDescriptionNoLinkView');
                }
            }
            catch (UserDBException $ex) {
                AddUserController::buildAddUserPage($page, true);
                if ($ex->getCode() == UserDBException::EMAILALREADYEXISTS)
                    $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="user_data_form_error_message">Dit e-mailadres is al in gebruik.</h2>';
                elseif ($ex->getCode() == UserDBException::CARDALREADYUSED)
                    $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="user_data_form_error_message">Dit kaartnummer is al in gebruik.</h2>';
                else
                    $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="user_data_form_error_message">Kan gebruiker niet toevoegen, probeer het opnieuw.</h2>';
            }
            catch (Exception $ex) {
                AddUserController::buildAddUserPage($page, true);
                $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="user_data_form_error_message">Kan gebruiker niet toevoegen, probeer het opnieuw.</h2>';
            }
        }
        else {
            AddUserController::buildAddUserPage($page, true);
            $page->data['UserDataFormTopView']['errMsgs'] = array_merge($page->data['UserDataFormTopView']['errMsgs'], $formTopViewErrMsgs);
            $page->data['UserDataFormPasswordView']['errMsgs'] = array_merge($page->data['UserDataFormPasswordView']['errMsgs'], $formPasswordViewErrMsgs);
            $page->data['UserDataFormMiddleView']['errMsgs'] = array_merge($page->data['UserDataFormMiddleView']['errMsgs'], $formMiddleViewErrMsgs);
        }
        $page->showWithMenu();
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
            $page->data['UserDataFormMiddleView']['dateOfBirth'] = '';
        }
        
        $page->data['UserDataFormTopView']['errMsgs'] = UserDataFormTopViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormTopView');
        
        $page->data['UserDataFormPasswordView']['errMsgs'] = UserDataFormPasswordViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormPasswordView');
        $page->data['UserDataFormMiddleView']['errMsgs'] = UserDataFormMiddleViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormMiddleView');
        
        $page->addView('addRenewUser/addUser/UserDataFormBottomView');
        
        $page->addExtraJsFile('barcodeScanner/BarcodeScanner.js');
        $page->addExtraJsFile('views/addRenewUser/UserDataFormBarcodeScanner.js');
        $page->addExtraJsFile('views/addRenewUser/userDataFormOnLoadHandler.js');
    }
}