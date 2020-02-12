<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Validator for the User Data Form Middle view.
 */

require_once __DIR__.'/../IValidator.php';
require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class UserDataFormMiddleViewValidator implements IValidator {
    
    public static function validate(array $data) {
        $errMsgs = array();     

        if ($data['first_name'] == '' || strlen($data['first_name']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['firstName'] = '<label class="form_label_error" for="first_name" id="form_label_error_first_name">Voer een geldige voornaam in.</label>';

        if ($data['last_name'] == '' || strlen($data['last_name']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['lastName'] = '<label class="form_label_error" for="last_name" id="form_label_error_last_name">Voer een geldige achternaam in.</label>';

        if ($data['street'] == '' || strlen($data['street']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['street'] = '<label class="form_label_error" for="street" id="form_label_error_street">Voer een geldige straatnaam in.</label>';

        if ($data['house_number'] == '' || strlen($data['house_number']) > DataValidationConfig::HOUSE_NUMBER_MAX_LENGTH)
            $errMsgs['houseNumber'] = '<label class="form_label_error" for="house_number" id="form_label_error_house_number">Voer een geldig huisnummer in.</label>';

        if ($data['city'] == '' || strlen($data['city']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['city'] = '<label class="form_label_error" for="city" id="form_label_error_city">Voer een geldige gemeente in.</label>';

        if (strlen($data['postal_code']) < DataValidationConfig::POSTAL_CODE_MIN_LENGTH || strlen($data['postal_code']) > DataValidationConfig::POSTAL_CODE_MAX_LENGTH)
            $errMsgs['postalCode'] = '<label class="form_label_error" for="postal_code" id="form_label_error_postal_code">Voer een geldige postcode in.</label>';

        if (!preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/', $data['date_of_birth']) || !checkdate((int)substr($data['date_of_birth'], 3, 2), (int)substr($data['date_of_birth'], 0,2), (int)substr($data['date_of_birth'], 6, 4)) || DateTime::createFromFormat("d/m/Y", $data['date_of_birth']) > new DateTime())
            $errMsgs['dateOfBirth'] = '<label class="form_label_error" for="form_label_example_date_of_birth" id="form_label_error_date_of_birth">Voer een geldige geboortedatum in.</label>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['firstName'] = '';
        $errMsgs['lastName'] = '';
        $errMsgs['street'] = '';
        $errMsgs['houseNumber'] = '';
        $errMsgs['city'] = '';
        $errMsgs['postalCode'] = '';
        $errMsgs['dateOfBirth'] = '';
        return $errMsgs;
    }
}