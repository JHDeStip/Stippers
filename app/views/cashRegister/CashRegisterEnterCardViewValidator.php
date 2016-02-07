<?php

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class CashRegisterEnterCardViewValidator implements IValidator {
    public static function validate (array $data){
        $errMsgs = array();

        if (!preg_match('/^[0-9]{1,'.DataValidationConfig::CARD_NUMBER_MAX_LENGTH.'}$/', $data['card_number']))
            $errMsgs['cardNumber'] = '<label class="form_label_error" for="card_number" id="form_label_error_card_number">Voer een geldig kaartnummer in.</label>';

        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        $errMsgs['cardNumber'] = '';
        return $errMsgs;
    }
}