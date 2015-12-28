<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Validator for the User Search Admin view.
 */

require_once __DIR__.'/../IValidator.php';
require_once __DIR__.'/../../config/DataValidationConfig.php';

abstract class UserSearchAdminViewValidator implements IValidator {
    
    public static function validate(array $data) {
        $errMsgs = array();
        
        if ($data['isAdmin'] != '' && (!ctype_digit($data['isAdmin']) || $data['isAdmin'] <  DataValidationConfig::ADMINPERMISSIONMIN || $data['isAdmin'] > DataValidationConfig::ADMINPERMISSIONMAX))
            $errMsgs['isAdmin'] = '<label class="form_label_error" for="is_admin">Selecteer een geldige optie.</label>';

        if ($data['isUserManager'] != '' && (!ctype_digit($data['isUserManager']) || $data['isUserManager'] < DataValidationConfig::USERMANAGERPERMISSIONMIN || $data['isUserManager'] > DataValidationConfig::USERMANAGERPERMISSIONMAX))
            $errMsgs['isUserManager'] = '<label class="form_label_error" for="is_user_manager">Selecteer een geldige optie.</label>';

        if ($data['isBrowserManager'] != '' && (!ctype_digit($data['isBrowserManager']) || $data['isBrowserManager'] < DataValidationConfig::BROWSERMANAGERPERMISSIONMIN || $data['isBrowserManager'] > DataValidationConfig::BROWSERMANAGERPERMISSIONMAX))
            $errMsgs['isBrowserManager'] = '<label class="form_label_error" for="is_browser_manager">Selecteer een geldige optie.</label>';
        
        return $errMsgs;
    }
    
    public static function initErrMsgs() {
        $errMsgs['isAdmin'] = '';
        $errMsgs['isUserManager'] = '';
        $errMsgs['isBrowserManager'] = '';
        
        return $errMsgs;
    }
}