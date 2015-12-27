<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Validator for the User Data Form Password view.
 */

require_once __DIR__.'/../IValidator.php';
require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class UserDataFormPasswordViewValidator implements IValidator {
    
    public static function validate(array $data) {
        $errMsgs = array();

        if (strlen($data['password']) < DataValidationConfig::PASSWORDMINLENGTH)
            $errMsgs['password'] = '<label class="form_label_error" for="email" id="form_label_error_password">Je wachtwoord moet minstens 8 karakters lang zijn.</label>';

        if ($data['repeat_password'] != $data['password'])
            $errMsgs['repeatPassword'] = '<label class="form_label_error" for="repeat_password" id="form_label_error_repeat_password">De twee wachtwoordvelden moeten gelijk zijn.</label>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['password'] = '';
        $errMsgs['repeatPassword'] = '';
        return $errMsgs;
    }
}