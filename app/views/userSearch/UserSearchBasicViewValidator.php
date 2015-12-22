<?php
/**
 * Created by PhpStorm.
 * User: Stan
 * Date: 6/02/2015
 * Time: 0:50
 */

require_once __DIR__.'/../IValidator.php';
require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class UserSearchBasicViewValidator implements IValidator {
    
    public static function validate(array $data) {
        $errMsgs = array();

        if (strlen($data['firstName']) > DataValidationConfig::STRINGMAXLENGTH)
            $errMsgs['firstName'] = '<label class="form_label_error" for="first_name">De voornaam mag maximaal uit 30 karakters bestaan.</label>';

        if (strlen($data['lastName']) > DataValidationConfig::STRINGMAXLENGTH)
            $errMsgs['lastName'] = '<label class="form_label_error" for="last_name">De achternaam mag maximaal uit 30 karakters bestaan.</label>';

        if (strlen($data['email']) > DataValidationConfig::EMAILMAXLENGTH)
            $errMsgs['email'] = '<label class="form_label_error" for="email">Het e-mailadres mag maximaal uit 50 karakters bestaan.</label>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['firstName'] = '';
        $errMsgs['lastName'] = '';
        $errMsgs['email'] = '';
        
        return $errMsgs;
    }
}