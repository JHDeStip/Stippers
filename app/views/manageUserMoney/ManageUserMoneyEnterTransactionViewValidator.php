<?php

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

require_once __DIR__.'/../../helperClasses/safeMath/SafeMath.php';

abstract class ManageUserMoneyEnterTransactionViewValidator implements IValidator {
    public static function validate (array $data){
        $errMsgs = array();
        
        //Get cents with safe precision
        $incrMoney = SafeMath::getCentsFromString($data['increase_money']);
        $decrMoney = SafeMath::getCentsFromString($data['decrease_money']);
        
        if ($data['increase_money'] != '' && ($incrMoney === false || $incrMoney > 99999))
            $errMsgs['global'] = '<h2 class="error_message" id="enter_transaction_form_error_message">Voer geldige bedragen in.</h2>';
        elseif ($data['increase_money'] < 0)
            $errMsgs['global'] = '<h2 class="error_message" id="enter_transaction_form_error_message">Je kan enkel positieve bedragen ingeven.</h2>';
        elseif ($data['decrease_money'] != '' && ($decrMoney === false || $decrMoney > 99999))
            $errMsgs['global'] = '<h2 class="error_message" id="enter_transaction_form_error_message">Voer geldige bedragen in.</h2>';
        elseif ($data['decrease_money'] < 0)
            $errMsgs['global'] = '<h2 class="error_message" id="enter_transaction_form_error_message">Je kan enkel positieve bedragen ingeven.</h2>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        
        return $errMsgs;
    }
}