<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the change password page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../config/SecurityConfig.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../views/changePassword/ChangePasswordViewValidator.php';

abstract class ChangePasswordController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Wachtwoord wijzigen';
        
        try {
            //Get all data of logged in user from database
            $_SESSION['Stippers']['ChangePassword']['user'] = UserDB::getFullUserById($_SESSION['Stippers']['user']->userId);
            //Build view
            ChangePasswordController::buildChangePasswordView($page);
        }
        catch (Exception $ex) {
            $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan gegevens niet ophalen uit de database';
            $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
            $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
        }
        
        $page->showWithMenu();
    } 
    
    public static function post() {
        if (isset($_POST['save'])) {
            $page = new Page();
            $page->data['title'] = 'Wachtwoord wijzigen';
            
            $errMsgs = ChangePasswordViewValidator::validate($_POST);
            if (empty($errMsgs)) {
                try {
                    $passwordSalt = UserDB::getPasswordSaltByUserId($_SESSION['Stippers']['user']->userId);
                    $oldPasswordHash = hash_pbkdf2('sha256', $_POST['old_password'], $passwordSalt, SecurityConfig::NPASSWORDHASHITERATIONS);
                    
                    //If the old password is incorrect, show an error
                    if ($_SESSION['Stippers']['ChangePassword']['user']->passwordHash != $oldPasswordHash) {
                        ChangePasswordController::buildChangePasswordView($page);
                        $page->data['ChangePasswordView']['errMsgs']['global'] = '<h2 class="error_message" id="change_password_form_error_message">Het oude wachtwoord is fout.</h2>';
                    }
                    //Update password
                    else {
                        $newPasswordHash = hash_pbkdf2('sha256', $_POST['new_password'], $passwordSalt, SecurityConfig::NPASSWORDHASHITERATIONS);
                        UserDB::updatePassword($_SESSION['Stippers']['ChangePassword']['user'], $newPasswordHash);
                        $_SESSION['Stippers']['user']->passwordHash = $newPasswordHash;
                        //Show success view
                        $page->data['SuccessMessageNoDescriptionWithLinkView']['successTitle'] = 'Wachtwoord succesvol gewijzigd';
                        $page->data['SuccessMessageNoDescriptionWithLinkView']['redirectUrl'] = 'profile';
                        $page->addView('success/SuccessMessageNoDescriptionWithLinkView');
                    }
                }
                catch (UserDBException $ex) {
                    //Show correct error message for errors
                    if ($ex->getCode() == UserDBException::USEROUTOFDATE) {
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorTitle'] = 'Wachtwoord niet gewijzigd';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorDescription'] = 'Iemand anders heeft je gegevens in tussentijd al gewijzigd.';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                        $page->addView('error/ErrorMessageWithDescriptionWithLinkView');
                    }
                    else {
                        ChangePasswordController::buildChangePasswordView($page);
                        $page->data['ChangePasswordView']['errMsgs']['global'] = '<h2 class="error_message" id="change_password_form_error_message">Kan wachtwoord niet wijzigen, probeer het opnieuw.</h2>';
                    }
                }
                catch (Exception $ex) {
                    ChangePasswordController::buildChangePasswordView($page);
                        $page->data['ChangePasswordView']['errMsgs']['global'] = '<h2 class="error_message" id="change_password_form_error_message">Kan wachtwoord niet wijzigen, probeer het opnieuw.</h2>';
                }
            }
            else {
                //If we had an error we show the page again with errors
                ChangePasswordController::buildChangePasswordView($page);
                $page->data['ChangePasswordView']['errMsgs'] = array_merge($page->data['ChangePasswordView']['errMsgs'], $errMsgs);
            }
            
            $page->showWithMenu();
        }
        //If cancel is clicked we show the page in it's initual states, so we call get.
        else
            ChangePasswordController::get();
    }
    
    /**
     * Builds the view to change the password.
     * 
     * @param Page $page page object to load data into
     */
    private static function buildChangePasswordView($page) {
        $page->data['ChangePasswordView']['change_password_formAction'] = $_SERVER['REQUEST_URI'];
        
        $page->data['ChangePasswordView']['oldPassword'] = '';
        $page->data['ChangePasswordView']['newPassword'] = '';
        $page->data['ChangePasswordView']['repeatNewPassword'] = '';
            
        $page->addView('changePassword/ChangePasswordView');
        $page->data['ChangePasswordView']['errMsgs'] = ChangePasswordViewValidator::initErrMsgs();
    }
}