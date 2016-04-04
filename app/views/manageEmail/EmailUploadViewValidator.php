<?php

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';
require_once __DIR__.'/../../config/EmailConfig.php';

abstract class EmailUploadViewValidator implements IValidator {
    public static function validate(array $data) {
        $errMsgs = array();

        //Check if a file is there to check
        if (isset($data['email_file'])) {
            //Check upload errors
            switch ($data['email_file']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errMsgs['global'] = '<h2 class="error_message" id="email_upload_form_error_message">Het bestand moet kleiner zijn dan 1MB.</h2>';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $errMsgs['global'] = '<h2 class="error_message" id="email_upload_form_error_message">Je hebt geen bestand geselecteerd.</h2>';
                    break;
                default:
                    $errMsgs['global'] = '<h2 class="error_message" id="email_upload_form_error_message">Uploaden mislukt, probeer opnieuw.</h2>';
                    break;
            }
            
            //If no error we check the filesize
            if ($data['email_file']['error'] == UPLOAD_ERR_OK && $data['email_file']['size'] > DataValidationConfig::EMAIL_FILE_MAX_SIZE)
                $errMsgs['global'] = '<h2 class="error_message" id="email_upload_form_error_message">Het bestand moet kleiner zijn dan 1MB.</h2>';
        }
        else
            $errMsgs['global'] = '<h2 class="error_message" id="email_upload_form_error_message">Het bestand moet kleiner zijn dan 1MB.</h2>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        
        return $errMsgs;
    }
}