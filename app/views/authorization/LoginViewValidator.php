<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Validator for the login view.
 */

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class LoginViewValidator implements IValidator {

    public static function validate(array $data) {
        $errMsgs = array();
        
        if (strlen($data['email']) > DataValidationConfig::EMAIL_MAX_LENGTH || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            $errMsgs['global'] = '<h2 class="error_message" id="login_form_error_message">E-mailadres en/of wachtwoord onjuist.</h2>';

        if (strlen($data['password']) < DataValidationConfig::PASSWORD_MIN_LENGTH || strlen($data['password']) > DataValidationConfig::PASSWORD_MAX_LENGTH)
            $errMsgs['global'] = '<h2 class="error_message" id="login_form_error_message">E-mailadres en/of wachtwoord onjuist.</h2>';

        return $errMsgs;
    }
    
        
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        return $errMsgs;
    }
}