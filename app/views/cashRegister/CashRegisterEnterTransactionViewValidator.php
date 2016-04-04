<?php

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

require_once __DIR__.'/../../helperClasses/safeMath/SafeMath.php';

abstract class CashRegisterEnterTransactionViewValidator implements IValidator {
    public static function validate (array $data){
        $errMsgs = array();
        
        //Get cents with safe precision
        $decrMoney = SafeMath::getCentsFromString($data['decrease_money']);
        
        if ($data['decrease_money'] == '')
            $errMsgs['global'] = '<h2 class="error_message" id="enter_transaction_form_error_message">Je hebt geen transactie ingegeven.</h2>';
        else if ($decrMoney === false || $decrMoney > 99999)
            $errMsgs['global'] = '<h2 class="error_message" id="enter_transaction_form_error_message">Voer een geldig bedrag in.</h2>';
        elseif ($data['decrease_money'] < 0)
            $errMsgs['global'] = '<h2 class="error_message" id="enter_transaction_form_error_message">Je kan enkel een positief bedrag ingeven.</h2>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        
        return $errMsgs;
    }
}