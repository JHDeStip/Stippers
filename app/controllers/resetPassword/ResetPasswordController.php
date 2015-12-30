<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the reset password page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../config/SecurityConfig.php';

require_once __DIR__.'/../../helperClasses/random/Random.php';

require_once __DIR__.'/../../helperClasses/email/Email.php';
require_once __DIR__.'/../../helperClasses/email/EmailException.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../views/resetPassword/ResetPasswordViewValidator.php';

abstract class ResetPasswordController implements IController {
    
    public static function get() {
        //If a user is logged in we redirect to home
        if (isset($_SESSION['Stippers']['user']))
            header('Location: home', true, 303);
        else {
            $page = new Page();
            $page->data['title'] = 'Wachtwoord resetten';
            $page->data['ResetPasswordView']['reset_password_formAction'] = $_SERVER['REQUEST_URI'];
            $page->data['ResetPasswordView']['email'] = '';
            $page->data['ResetPasswordView']['errMsgs'] = ResetPasswordViewValidator::initErrMsgs();
            $page->addView('resetPassword/ResetPasswordView');
            $page->showWithMenu();
        }
    }
    
    public static function post() {
        $page = new Page();
        $page->data['title'] = 'Wachtwoord resetten';
        
        $errMsgs = ResetPasswordViewValidator::validate($_POST);
        
        if (empty($errMsgs)) {
            try {
                //Get the user's password salt and calculate password hash
                $passwordSalt = UserDB::getPasswordSaltByEmail($_POST['email']);
                $newPassword = Random::getPassword();
                $newPasswordHash = hash_pbkdf2("sha256", $newPassword, $passwordSalt, SecurityConfig::NPASSWORDHASHITERATIONS);
            
                //Get user from database and reset password.
                $user = UserDB::getBasicUserByEmail($_POST['email']);
                UserDB::resetPassword($_POST['email'], $newPasswordHash);
                
                //Show success message
                $page->data['ResetSuccessfulView']['redirectUrl'] = 'login';
                $page->addView('resetPassword/ResetSuccessfulView');
                
                //Send email with password
                $failedEmails = Email::sendEmails('ResetPassword.html', 'JH De Stip - Wachtwoord reset', 'info@stip.be', [$user], array($user->userId => array('newPassword' => $newPassword)));
                //If failedEmails is not empty the mail was not sent
                if (!empty($failedEmails)) {
                    $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] = 'Kan e-mail met nieuwe wachtwoord niet verzenden.';
                    $page->addView('error/ErrorMessageNoDescriptionNoLinkView');
                }
            }
            catch (UserDBException $ex) {
                $page->data['ResetPasswordView']['reset_password_formAction'] = $_SERVER['REQUEST_URI'];
                $page->data['ResetPasswordView']['email'] = $_POST['email'];
                $page->data['ResetPasswordView']['errMsgs'] = ResetPasswordViewValidator::initErrMsgs();
                
                if ($ex->getCode() == UserDBException::NOUSERFOREMAIL)
                    $page->data['ResetPasswordView']['errMsgs']['global'] = '<h2 class="error_message" id="reset_password_form_error_message">Er is geen gebruiker met dit e-mailadres.</h2>';
                else
                    $page->data['ResetPasswordView']['errMsgs']['global'] = '<h2 class="error_message" id="reset_password_form_error_message">Kan wachtwoord niet resetten, probeer het opnieuw.</h2>';
                
                $page->addView('resetPassword/ResetPasswordView');
            }
            catch (EmailException $ex) {
                $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] = 'Kan e-mail met nieuwe wachtwoord niet verzenden.';
                $page->addView('error/ErrorMessageNoDescriptionNoLinkView');
            }
            catch (Exception $ex) {
                $page->data['ResetPasswordView']['reset_password_formAction'] = $_SERVER['REQUEST_URI'];
                $page->data['ResetPasswordView']['email'] = $_POST['email'];
                $page->data['ResetPasswordView']['errMsgs']['global'] = '<h2 class="error_message" id="reset_password_form_error_message">Kan wachtwoord niet resetten, probeer het opnieuw.</h2>';
                $page->addView('resetPassword/ResetPasswordView');
            }
        }
        else {
            $page->data['ResetPasswordView']['reset_password_formAction'] = $_SERVER['REQUEST_URI'];
            $page->data['ResetPasswordView']['email'] = $_POST['email'];
            $page->data['ResetPasswordView']['errMsgs'] = ResetPasswordViewValidator::initErrMsgs();
            $page->data['ResetPasswordView']['errMsgs'] = array_merge($page->data['ResetPasswordView']['errMsgs'], $errMsgs);
            $page->addView('resetPassword/ResetPasswordView');
        }
        
        $page->showWithMenu();
    }
}
