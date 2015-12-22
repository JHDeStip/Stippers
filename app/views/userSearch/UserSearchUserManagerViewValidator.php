<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Validator for the User Search User Manager view.
 */

require_once __DIR__.'/../IValidator.php';
require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class UserSearchUserManagerViewValidator implements IValidator {
    
    public static function validate(array $data) {
        $errMsgs = array();

        if (strlen($data['balance']) > DataValidationConfig::BALANCEMAXLENGTH)
            $errMsgs['balance'] = '<label class="form_label_error" for="balance">Het saldo mag maximaal uit 6 karakters bestaan.</label>';

        if (strlen($data['phone']) > DataValidationConfig::PHONEMAXLENGTH)
            $errMsgs['phone'] = '<label class="form_label_error" for="phone">Het telefoonnummer mag maximaal uit 14 karakters bestaan.</label>';

        if (strlen($data['dateOfBirth']) > DataValidationConfig::DATEMAXLENGTH)
            $errMsgs['dateOfBirth'] = '<label class="form_label_error" for="date_of_birth">De geboortedatum mag maximaal uit 10 karakters bestaan.</label>';

        if (strlen($data['street']) > DataValidationConfig::STRINGMAXLENGTH)
            $errMsgs['street'] = '<label class="form_label_error" for="street">De straat mag maximaal uit 30 karakters bestaan.</label>';

        if (strlen($data['houseNumber']) > DataValidationConfig::HOUSENUMBERMAXLENGTH)
            $errMsgs['houseNumber'] = '<label class="form_label_error" for="house_number">Het huisnummer mag maximaal uit 4 karakters bestaan.</label>';

        if (strlen($data['city']) > DataValidationConfig::STRINGMAXLENGTH)
            $errMsgs['city'] = '<label class="form_label_error" for="city">De gemeente mag maximaal uit 30 karakters bestaan.</label>';

        if (strlen($data['postalCode']) > DataValidationConfig::POSTALCODEMAXLENGTH)
            $errMsgs['postalCode'] = '<label class="form_label_error" for="postalCode">De postcode mag maximaal uit 6 karakters bestaan.</label>';

        if (strlen($data['country']) > DataValidationConfig::STRINGMAXLENGTH)
            $errMsgs['country'] = '<label class="form_label_error" for="country">Het land mag maximaal uit 30 karakters bestaan.</label>';

        if (strlen($data['cardNumber']) > DataValidationConfig::CARDNUMBERMAXLENGTH)
            $errMsgs['cardNumber'] = '<label class="form_label_error" for="country">Het kaartnummer mag maximaal uit 8 karakters bestaan.</label>';

        if (strlen($data['membershipYear']) > DataValidationConfig::YEARMAXLENGTH)
            $errMsgs['membershipYear'] = '<label class="form_label_error" for="membership_year">Het lidjaar mag maximaal uit 4 karakters bestaan.</label>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
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