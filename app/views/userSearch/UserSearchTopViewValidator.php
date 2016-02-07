<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Validator for the User Search Top view.
 */

require_once __DIR__.'/../IValidator.php';
require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class UserSearchTopViewValidator implements IValidator {
    
    public static function validate(array $data) {
        $errMsgs = array();

        if (strlen($data['firstName']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['firstName'] = '<label class="form_label_error" for="first_name">De voornaam mag maximaal uit 30 karakters bestaan.</label>';

        if (strlen($data['lastName']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['lastName'] = '<label class="form_label_error" for="last_name">De achternaam mag maximaal uit 30 karakters bestaan.</label>';

        if (strlen($data['email']) > DataValidationConfig::EMAIL_MAX_LENGTH)
            $errMsgs['email'] = '<label class="form_label_error" for="email">Het e-mailadres mag maximaal uit 50 karakters bestaan.</label>';
        
        if (strlen($data['balance']) > DataValidationConfig::MONEY_MAX_LENGTH)
            $errMsgs['balance'] = '<label class="form_label_error" for="balance">Het saldo mag maximaal uit 6 karakters bestaan.</label>';

        if (strlen($data['phone']) > DataValidationConfig::PHONE_MAX_LENGTH)
            $errMsgs['phone'] = '<label class="form_label_error" for="phone">Het telefoonnummer mag maximaal uit 14 karakters bestaan.</label>';

        if (strlen($data['dateOfBirth']) > DataValidationConfig::DATE_MAX_LENGTH)
            $errMsgs['dateOfBirth'] = '<label class="form_label_error" for="date_of_birth">De geboortedatum mag maximaal uit 10 karakters bestaan.</label>';

        if (strlen($data['street']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['street'] = '<label class="form_label_error" for="street">De straat mag maximaal uit 30 karakters bestaan.</label>';

        if (strlen($data['houseNumber']) > DataValidationConfig::HOUSE_NUMBER_MAX_LENGTH)
            $errMsgs['houseNumber'] = '<label class="form_label_error" for="house_number">Het huisnummer mag maximaal uit 4 karakters bestaan.</label>';

        if (strlen($data['city']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['city'] = '<label class="form_label_error" for="city">De gemeente mag maximaal uit 30 karakters bestaan.</label>';

        if (strlen($data['postalCode']) > DataValidationConfig::POSTAL_CODE_MAX_LENGTH)
            $errMsgs['postalCode'] = '<label class="form_label_error" for="postalCode">De postcode mag maximaal uit 6 karakters bestaan.</label>';

        if (strlen($data['country']) > DataValidationConfig::STRING_MAX_LENGTH)
            $errMsgs['country'] = '<label class="form_label_error" for="country">Het land mag maximaal uit 30 karakters bestaan.</label>';

        if (strlen($data['cardNumber']) > DataValidationConfig::CARD_NUMBER_MAX_LENGTH)
            $errMsgs['cardNumber'] = '<label class="form_label_error" for="country">Het kaartnummer mag maximaal uit 8 karakters bestaan.</label>';
        
        if (strlen($data['membershipYear']) > DataValidationConfig::YEAR_MAX_LENGTH)
            $errMsgs['membershipYear'] = '<label class="form_label_error" for="membership_year">Het lidjaar mag maximaal uit 4 karakters bestaan.</label>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['firstName'] = '';
        $errMsgs['lastName'] = '';
        $errMsgs['email'] = '';
        $errMsgs['balance'] = '';
        $errMsgs['phone'] = '';
        $errMsgs['dateOfBirth'] = '';
        $errMsgs['street'] = '';
        $errMsgs['houseNumber'] = '';
        $errMsgs['city'] = '';
        $errMsgs['postalCode'] = '';
        $errMsgs['country'] = '';
        $errMsgs['cardNumber'] = '';
        $errMsgs['membershipYear'] = '';
        
        return $errMsgs;
    }
}