<?php

require_once __DIR__."/../IValidator.php";

abstract class LoginViewValidator implements IValidator {
    const EMAILLENGTH = 50;
    const PASSWORDMINLENGTH = 8;

    public static function validate($postData)
    {
        $errMsgs = null;
        
        if ($postData["email"] == "" || strlen($postData["email"]) > LoginViewValidator::EMAILLENGTH || !filter_var($postData["email"], FILTER_VALIDATE_EMAIL))
            $errMsgs["global"] = '<h2 class="error_message" id="login_form_error_message">E-mailadres en/of wachtwoord onjuist.</h2>';

        if (strlen($postData["password"]) < LoginViewValidator::PASSWORDMINLENGTH)
            $errMsgs["global"] = '<h2 class="error_message" id="login_form_error_message">E-mailadres en/of wachtwoord onjuist.</h2>';

        return $errMsgs;
    }
}