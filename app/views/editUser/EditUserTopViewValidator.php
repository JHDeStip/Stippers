<?php

require_once __DIR__.'/../IValidator.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class EditUserTopViewValidator implements IValidator {
    public static function validate(array $data) {
            $errMsgs = array();

        if ($data['EditUserTopView']['email'] == "" || strlen($data['EditUserTopView']['email']) > DataValidationConfig::EMAILLENGTH || !filter_var($data['EditUserTopView']['email'], FILTER_VALIDATE_EMAIL))
            $errMsgs['email'] = '<label class="form_label_error" for="email" id="form_label_error_email">Voer een geldig e-mailadres in.</label>';

        if ($data['EditUserTopView']['repeatEmail'] != $data['EditUserTopView']['email'])
            $errMsgs['repeatEmail'] = '<label class="form_label_error" for="email" id="form_label_error_repeat_email">De twee e-mailadressen moeten gelijk zijn.</label>';

        if ($data['EditUserTopView']['firstName'] == "" || strlen($data['EditUserTopView']['firstName']) > DataValidationConfig::STRINGLENGTH)
            $errMsgs['firstName'] = '<label class="form_label_error" for="first_name" id="form_label_error_first_name">Voer een geldige voornaam in.</label>';

        if ($data['EditUserTopView']['lastName'] == "" || strlen($data['EditUserTopView']['lastName']) > DataValidationConfig::STRINGLENGTH)
            $errMsgs['lastName'] = '<label class="form_label_error" for="last_name" id="form_label_error_last_name">Voer een geldige achternaam in.</label>';

        if ($data['EditUserTopView']['street'] == "" || strlen($data['EditUserTopView']['street']) > DataValidationConfig::STRINGLENGTH)
            $errMsgs['street'] = '<label class="form_label_error" for="street" id="form_label_error_street">Voer een geldige straatnaam in.</label>';

        if ($data['EditUserTopView']['houseNumber'] == "" || strlen($data['EditUserTopView']['houseNumber']) > DataValidationConfig::HOUSENUMBERLENGTH)
            $errMsgs['houseNumber'] = '<label class="form_label_error" for="house_number" id="form_label_error_house_number">Voer een geldig huisnummer in.</label>';

        if ($data['EditUserTopView']['city'] == "" || strlen($data['EditUserTopView']['city']) > DataValidationConfig::STRINGLENGTH)
            $errMsgs['city'] = '<label class="form_label_error" for="city" id="form_label_error_city">Voer een geldige gemeente in.</label>';

        if (strlen($data['EditUserTopView']['postalCode']) < DataValidationConfig::POSTALCODEMINLENGTH || strlen($data['EditUserTopView']['postalCode']) > DataValidationConfig::POSTALCODEMAXLENGTH)
            $errMsgs['postalCode'] = '<label class="form_label_error" for="postal_code" id="form_label_error_postal_code">Voer een geldige postcode in.</label>';

        if ($data['EditUserTopView']['country'] == "" || strlen($data['EditUserTopView']['country']) > DataValidationConfig::STRINGLENGTH)
            $errMsgs['country'] = '<label class="form_label_error" for="country" id="form_label_error_country">Voer een geldig land in.</label>';

        if (!$data['EditUserTopView']['phone'] == "" && !preg_match("/^[0-9]{9,16}$/", $data['EditUserTopView']['phone']))
            $errMsgs['phone'] = '<label class="form_label_error" for="phone" id="form_label_error_phone">Voer een geldig telefoonnummer in.</label>';

        if ($data['EditUserTopView']['dateOfBirth'] == "" || !preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $data['EditUserTopView']['dateOfBirth']) || !checkdate((int)substr($data['EditUserTopView']['dateOfBirth'], 3, 2), (int)substr($data['EditUserTopView']['dateOfBirth'], 0,2), (int)substr($data['EditUserTopView']['dateOfBirth'], 6, 4)) || DateTime::createFromFormat("d/m/Y", $data['EditUserTopView']['dateOfBirth']) > new DateTime())
            $errMsgs['dateOfBirth'] = '<label class="form_label_error" for="form_label_example_date_of_birth" id="form_label_error_date_of_birth">Voer een geldige geboortedatum in.</label>';

        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['global'] = '';
        
        $errMsgs['email'] = '';
        $errMsgs['repeatEmail'] = '';
        $errMsgs['firstName'] = '';
        $errMsgs['lastName'] = '';
        $errMsgs['dateOfBirth'] = '';
        $errMsgs['phone'] = '';
        $errMsgs['street'] = '';
        $errMsgs['houseNumber'] = '';
        $errMsgs['city'] = '';
        $errMsgs['postalCode'] = '';
        $errMsgs['country'] = '';
        
        return $errMsgs;
    }
}