<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Validator for the User Data Form Top view.
 */

require_once __DIR__.'/../IValidator.php';
require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class UserDataFormTopViewValidator implements IValidator {
    
    public static function validate(array $data) {
        $errMsgs = array();

        if (!preg_match('/^[0-9]{1,'.DataValidationConfig::CARD_NUMBER_MAX_LENGTH.'}$/', $data['card_number']))
            $errMsgs['cardNumber'] = '<label class="form_label_error" for="card_number" id="form_label_error_card_number">Voer een geldig kaartnummer in.</label>';

        if (strlen($data['email']) > DataValidationConfig::EMAIL_MAX_LENGTH || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            $errMsgs['email'] = '<label class="form_label_error" for="email" id="form_label_error_email">Voer een geldig e-mailadres in.</label>';

        if ($data['repeat_email'] != $data['email'])
            $errMsgs['repeatEmail'] = '<label class="form_label_error" for="repeat_email" id="form_label_error_repeat_email">De twee e-mailadressen moeten gelijk zijn.</label>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        $errMsgs['cardNumber'] = '';
        $errMsgs['email'] = '';
        $errMsgs['repeatEmail'] = '';
        return $errMsgs;
    }
}