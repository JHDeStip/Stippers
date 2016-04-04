<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Validator for the Renew User Search Top view.
 */

require_once __DIR__.'/../../IValidator.php';
require_once __DIR__.'/../../../config/DataValidationConfig.php';

abstract class RenewUserSearchViewValidator implements IValidator {
    
    public static function validate(array $data) {
        $errMsgs = array();

        if (strlen($data['first_name']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['firstName'] = '<label class="form_label_error" for="first_name">De voornaam mag maximaal uit '.DataValidationConfig::STRING_MAX_LENGTH.' karakters bestaan.</label>';

        if (strlen($data['last_name']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['lastName'] = '<label class="form_label_error" for="last_name">De achternaam mag maximaal uit '.DataValidationConfig::STRING_MAX_LENGTH.' karakters bestaan.</label>';

        if (strlen($data['email']) > DataValidationConfig::EMAIL_MAX_LENGTH)
            $errMsgs['email'] = '<label class="form_label_error" for="email">Het e-mailadres mag maximaal uit '.DataValidationConfig::EMAIL_MAX_LENGTH.' karakters bestaan.</label>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['firstName'] = '';
        $errMsgs['lastName'] = '';
        $errMsgs['email'] = '';
        
        return $errMsgs;
    }
}