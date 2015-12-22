<?php

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class LoginViewValidator implements IValidator {

    public static function validate(array $data) {
        $errMsgs = array();
        
        if ($data['email'] == '' || strlen($data['email']) > DataValidationConfig::EMAILMAXLENGTH || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            $errMsgs['global'] = '<h2 class="error_message" id="login_form_error_message">E-mailadres en/of wachtwoord onjuist.</h2>';

        if (strlen($data['password']) < DataValidationConfig::PASSWORDMINLENGTH || strlen($data['password']) > DataValidationConfig::PASSWORDMAXLENGTH)
            $errMsgs['global'] = '<h2 class="error_message" id="login_form_error_message">E-mailadres en/of wachtwoord onjuist.</h2>';

        return $errMsgs;
    }
    
        
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        return $errMsgs;
    }
}