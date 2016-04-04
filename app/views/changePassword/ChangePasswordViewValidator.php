<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Validator for the change password view.
 */

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class ChangePasswordViewValidator implements IValidator {

    public static function validate(array $data) {
        $errMsgs = array();
        
        if (strlen($data['new_password']) < DataValidationConfig::PASSWORD_MIN_LENGTH || strlen($data['new_password']) > DataValidationConfig::PASSWORD_MAX_LENGTH)
            $errMsgs['newPassword'] = '<label class="form_label_error" id="form_label_error_new_password" for="new_password">Je wachtwoord moet minstens 8 karakters lang zijn.</label>';

        if ($data['repeat_new_password'] != $data['new_password'])
            $errMsgs['repeatNewPassword'] = '<label class="form_label_error" id="form_label_error_repeat_new_password" for="repeat_new_password">De twee wachtwoordvelden moeten gelijk zijn.</label>';
        
        return $errMsgs;
    }
    
        
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        $errMsgs['newPassword'] = '';
        $errMsgs['repeatNewPassword'] = '';
        
        return $errMsgs;
    }
}