<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the renew user page.
 */

require_once __DIR__.'/../../IController.php';
require_once __DIR__.'/../../../helperClasses/Page.php';

require_once __DIR__.'/../../../config/EmailConfig.php';

require_once __DIR__.'/../../../helperClasses/random/Random.php';

require_once __DIR__.'/../../../config/AddOrRenewUserConfig.php';

require_once __DIR__.'/../../../helperClasses/email/Email.php';
require_once __DIR__.'/../../../helperClasses/email/EmailException.php';

require_once __DIR__.'/../../../models/user/User.php';
require_once __DIR__.'/../../../models/user/UserDB.php';
require_once __DIR__.'/../../../models/user/UserDBException.php';

require_once __DIR__.'/../../../models/moneyTransaction/MoneyTransaction.php';
require_once __DIR__.'/../../../models/moneyTransaction/MoneyTransactionDB.php';
require_once __DIR__.'/../../../models/moneyTransaction/MoneyTransactionDBException.php';

require_once __DIR__.'/../../../views/addRenewUser/UserDataFormTopViewValidator.php';
require_once __DIR__.'/../../../views/addRenewUser/UserDataFormPasswordViewValidator.php';
require_once __DIR__.'/../../../views/addRenewUser/UserDataFormMiddleViewValidator.php';

abstract class RenewUserController implements IController {
    
    public static function get() {
        if (!isset($_GET['user'])) {
            //No user id given so redirect to the manageuser
            //page to search a user
            header('Location: renewusersearch', true, 303);
        }
        else {
            $page = new Page();
            $page->data['title'] = 'Gebruiker hernieuwen';
            try {
                $_SESSION['Stippers']['RenewUser']['user'] = UserDB::getFullUserById($_GET['user']);
                RenewUserController::buildRenewUserPage($page, false);
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
                $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kon gegevens van gebruiker niet ophalen';
                $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
            }
            $page->showWithMenu();
        }
    }
    
    public static function post() {
        if (isset($_POST['save'])) {
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
                $newUser->isBrowserManager = $_SESSION['Stippers']['RenewUser']['user']->isBrowserManager;
                $newUser->isMoneyManager = $_SESSION['Stippers']['RenewUser']['user']->isMoneyManager;
                $newUser->creationTime = $_SESSION['Stippers']['RenewUser']['user']->creationTime;
                
                //Renew the user
                try {
                    UserDB::renewMembership($_SESSION['Stippers']['RenewUser']['user'], $newUser, $_POST['card_number']);
                    $page->addView('addRenewUser/renewUser/SuccessfullyRenewedView');
                    
                    //Send welcome mail
                    try {
                        $failedEmails = Email::sendEmails('WelcomeOldMember.html', 'JH DE Stip - Welkom', EmailConfig::FROMADDRESS, [$newUser], null);
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
                        $executingBrowserName = BrowserDB::getBrowserById($_SESSION['Stippers']['browser']->browserId)->name;
                        $trans = new MoneyTransaction(null, $newUser->userId, $newUser->balance, AddOrRenewUserConfig::NEWORRENEWEDUSERBONUS, 0, 0, true, null, $executingBrowserName, null);
                        MoneyTransactionDB::addTransaction($newUser, $trans);
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
                    if ($ex->getCode() == UserDBException::USERALREADYMEMBER) {
                        $page->addView('addRenewUser/renewUser/UserAlreadyMemberView');
                    }
                    elseif ($ex->getCode() == UserDBException::USEROUTOFDATE) {
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorTitle'] = 'Gebruiker niet hernieuwd';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorDescription'] = 'Iemand anders heeft de gebruiker in tussentijd al gewijzigd.';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                        $page->addView('error/ErrorMessageWithDescriptionWithLinkView');
                    }
                    else {
                        RenewUserController::buildRenewUserPage($page, true);
                        if ($ex->getCode() == UserDBException::EMAILALREADYEXISTS)
                            $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="user_data_form_error_message">Dit e-mailadres is al in gebruik.</h2>';
                        elseif ($ex->getCode() == UserDBException::CARDALREADYUSED)
                            $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="user_data_form_error_message">Dit kaartnummer is al in gebruik.</h2>';
                        else
                            $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="user_data_form_error_message">Kan gebruiker niet hernieuwen, probeer het opnieuw.</h2>';
                    }
                }
                catch (Exception $ex) {
                    RenewUserController::buildRenewUserPage($page, true);
                    $page->data['UserDataFormTopView']['errMsgs']['global'] = '<h2 class="error_message" id="user_data_form_error_message">Kan gebruiker niet hernieuwen, probeer het opnieuw.</h2>';
                }
            }
            else {
                RenewUserController::buildRenewUserPage($page, true);
                $page->data['UserDataFormTopView']['errMsgs'] = array_merge($page->data['UserDataFormTopView']['errMsgs'], $formTopViewErrMsgs);
                $page->data['UserDataFormMiddleView']['errMsgs'] = array_merge($page->data['UserDataFormMiddleView']['errMsgs'], $formMiddleViewErrMsgs);
            }
            $page->showWithMenu();
        }
        else
            header('Location: renewusersearch', true, 303);
    }
    
    /**
     * Builds the page for the user input.
     * 
     * @param Page $page page to add the views to
     */
    private static function buildRenewUserPage(Page $page, $saveMode) {
        $page->addView('addRenewUser/renewUser/RenewUserTopView');
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
            $page->data['UserDataFormTopView']['email'] = $_SESSION['Stippers']['RenewUser']['user']->email;
            $page->data['UserDataFormTopView']['repeatEmail'] = $_SESSION['Stippers']['RenewUser']['user']->email;
            $page->data['UserDataFormMiddleView']['firstName'] = $_SESSION['Stippers']['RenewUser']['user']->firstName;
            $page->data['UserDataFormMiddleView']['lastName'] = $_SESSION['Stippers']['RenewUser']['user']->lastName;
            $page->data['UserDataFormMiddleView']['street'] = $_SESSION['Stippers']['RenewUser']['user']->street;
            $page->data['UserDataFormMiddleView']['houseNumber'] = $_SESSION['Stippers']['RenewUser']['user']->houseNumber;
            $page->data['UserDataFormMiddleView']['city'] = $_SESSION['Stippers']['RenewUser']['user']->city;
            $page->data['UserDataFormMiddleView']['postalCode'] = $_SESSION['Stippers']['RenewUser']['user']->postalCode;
            $page->data['UserDataFormMiddleView']['country'] = $_SESSION['Stippers']['RenewUser']['user']->country;
            $page->data['UserDataFormMiddleView']['phone'] = $_SESSION['Stippers']['RenewUser']['user']->phone;
            $page->data['UserDataFormMiddleView']['dateOfBirth'] = $_SESSION['Stippers']['RenewUser']['user']->dateOfBirth;
        }
        $page->data['UserDataFormTopView']['errMsgs'] = UserDataFormTopViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormTopView');
        $page->data['UserDataFormMiddleView']['errMsgs'] = UserDataFormMiddleViewValidator::initErrMsgs();
        $page->addView('addRenewUser/UserDataFormMiddleView');
        $page->addView('addRenewUser/renewUser/UserDataFormBottomView');
        
        $page->addExtraJsFile('barcodeScanner/BarcodeScanner.js');
        $page->addExtraJsFile('views/addRenewUser/UserDataFormBarcodeScanner.js');
        $page->addExtraJsFile('views/addRenewUser/userDataFormOnLoadHandler.js');
    }
}