<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Validator for the send email to users view.
 */

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class SendEmailToUsersViewValidator implements IValidator {

    public static function validate(array $data) {
        $errMsgs = array();
        
        if ($data['subject'] == '' || strlen($data['subject']) > DataValidationConfig::EMAIL_SUBJECT_MAX_LENGTH)
            $errMsgs['subject'] = '<label class="form_label_error" for="subject">Voer een geldig onderwerp in.</label>';
        
        if ($data['email_file'] == '' || strlen($data['email_file']) > DataValidationConfig::EMAIL_FILE_NAME_MAX_LENGTH)
            $errMsgs['email_file'] = '<label class="form_label_error" for="email_file">Selecteer een geldig bestand.</label>';

        return $errMsgs;
    }
    
        
    public static function initErrMsgs() {
        $errMsgs['subject'] = '';
        $errMsgs['emailFile'] = '';
            
        return $errMsgs;
    }
}