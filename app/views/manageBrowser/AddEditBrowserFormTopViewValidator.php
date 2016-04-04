<?php

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class AddEditBrowserTopViewValidator implements IValidator {
    public static function validate(array $data) {
        $errMsgs = array();

        if ($data['browser_name'] == '' || strlen($data['browser_name']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['browserName'] = '<label class="form_label_error" for="browser_name" id="form_label_error_browser_name">Voer een geldige browsernaam in.</label>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        $errMsgs['browserName'] = '';
        
        return $errMsgs;
    }
}