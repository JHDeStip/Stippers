<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Validator for the reset password view.
 */

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class ResetPasswordViewValidator implements IValidator {

    public static function validate(array $data) {
        $errMsgs = array();
        
        if (strlen($data['email']) > DataValidationConfig::EMAIL_MAX_LENGTH || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            $errMsgs['email'] = '<label class="form_label_error" for="email">Voer een geldig e-mailadres in.</label>';

        return $errMsgs;
    }
    
        
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        $errMsgs['email'] = '';
        
        return $errMsgs;
    }
}