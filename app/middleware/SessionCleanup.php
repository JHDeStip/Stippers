<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Middleware to do some session cleanup. It removes data from session that's irrelevant for the page you're going to.
 */

require_once "IMiddleware.php";

abstract class SessionCleanup implements IMiddleware {
    
    public static function run(array $requestData) {
        
        if ($requestData['requestedPage'] != 'manageuser' && $requestData['requestedPage'] != 'edituser' && $requestData['requestedPage'] != 'sendemailtousers' && $requestData['requestedPage'] != 'usertransactions')
            unset($_SESSION['Stippers']['ManageUserSearch']);
        
        if ($requestData['requestedPage'] != 'edituser')
            unset($_SESSION['Stippers']['EditUser']);
        
        if ($requestData['requestedPage'] != 'editbrowser')
            unset($_SESSION['Stippers']['EditBrowser']);

        if ($requestData['requestedPage'] != 'renewuser')
            unset($_SESSION['Stippers']['RenewUser']);
        
        if ($requestData['requestedPage'] != 'profile')
            unset($_SESSION['Stippers']['Profile']);
        
        if ($requestData['requestedPage'] != 'changepassword')
            unset($_SESSION['Stippers']['ChangePassword']);
        
        if ($requestData['requestedPage'] != 'cashregister')
            unset($_SESSION['Stippers']['CashRegister']);
        
        return true;
    }
}
        