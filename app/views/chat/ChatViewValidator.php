<?php

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class ChatViewValidator implements IValidator {
    public static function validate(array $data) {
        $errMsgs = array();

        if ($data['new_message'] == '' || strlen($data['new_message']) > DataValidationConfig::CHAT_MESSAGE_MAX_LENGTH)
            $errMsgs['global'] = '<h2 class="error_message" id="new_message_form_error_message">Geef een geldig bericht in.</h2>';

        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        
        return $errMsgs;
    }
}