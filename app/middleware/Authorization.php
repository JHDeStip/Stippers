<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Middleware to do authentication and authorization.
 */

require_once "IMiddleware.php";

require_once __DIR__."/../config/DomainConfig.php";
require_once __DIR__."/../config/APIConfig.php";

require_once __DIR__."/../controllers/authorization/LoginController.php";
require_once __DIR__."/../controllers/authorization/DBErrorController.php";
require_once __DIR__."/../controllers/authorization/ForcedLogoutController.php";
require_once __DIR__."/../controllers/authorization/AccessDeniedController.php";

require_once __DIR__."/../models/browser/Browser.php";
require_once __DIR__."/../models/browser/BrowserDB.php";
require_once __DIR__."/../models/browser/BrowserDBException.php";

require_once __DIR__.'/../models/user/User.php';
require_once __DIR__.'/../models/user/UserDB.php';
require_once __DIR__.'/../models/user/UserDBException.php';

abstract class Authorization implements IMiddleware {
    
    public static function run(array $requestData) {
        
        //Make sure we have a session
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        /*
        //Count hits
        if (isset($_SESSION['Stippers']['hits']))
            $_SESSION['Stippers']['hits']++;
        else
            $_SESSION['Stippers']['hits'] = 0;
        echo $_SESSION['Stippers']['hits'];
        */
        //If we have login in our post this means we are on the login page in and we must call the login controller.
        //The login controller will load
        if (isset($_POST['login']))
            LoginController::post();
        
        $errorGettingUser = false;
        $errorGettingBrowser = false;
        
        //Check if we have data about a user in session, in which case renew it.
        //Also check if we're not logging in because then we already have fresh user data.
        if (isset($_SESSION['Stippers']['user']) && !isset($_POST['login'])) {
            try {
                //Gets user from database. This gets the user only if he's a member this year or if it's the admin account.
                $newUser = UserDB::getAuthUserById($_SESSION['Stippers']['user']->userId);
                
                //If the user's password has changed we immediately log out!
                if ($_SESSION['Stippers']['user']->passwordHash != $newUser->passwordHash) {
                    session_destroy();
                    ForcedLogoutController::get();
                    return false;
                }
                else
                    $_SESSION['Stippers']['user'] = $newUser;
            }
            catch (Exception $ex) {
                session_destroy();
                ForcedLogoutController::get();
                return false;
            }
        }
        
        //Get the browser data for this browser
        if (isset($_COOKIE['stippersAuthorization'])) {
            try {
                $_SESSION['Stippers']['browser'] = BrowserDB::getBasicBrowserByUuid($_COOKIE['stippersAuthorization']);
            }
            catch (BrowserDBException $ex) {
                //unset because we don't want to use old data
                unset($_SESSION['Stippers']['browser']);
                
                if ($ex->getCode() != BrowserDBException::NOBROWSERFORUUID)
                    $errorGettingBrowser = true;
            }
            catch (Exception $ex) {
                //unset because we don't want to use old data
                unset($_SESSION['Stippers']['browser']);
                
                $errorGettingBrowser = true;
            }
        }
                                
        //We can now check the page access permissions.
        require_once __DIR__.'/../config/PageAccessPermissions.php';
        
        //Default to inaccessible for everyone
        $everyone = false;
        $member = false;
        $admin = false;
        $userManager = false;
        $hintManager = false;
        $browserManager = false;
        $moneyManager = false;
        $addRenewUserBrowser = false;
        $checkInBrowser = false;
        $cashRegisterBrowser = false;
        $apiKey = false;

        //If permissions for the requested page are defined we override the
        //defaults with these.
        if (isset($_PERMISSIONS[$requestData['requestedPage']])) {
            $permissions = $_PERMISSIONS[$requestData['requestedPage']];
            
            if (isset($permissions['EVERYONE']))
                $everyone = $permissions['EVERYONE'];
            if (isset($permissions['MEMBER']))
                $member = $permissions['MEMBER'];
            if (isset($permissions['ADMIN']))
                $admin = $permissions['ADMIN'];
            if (isset($permissions['USERMANAGER']))
                $userManager = $permissions['USERMANAGER'];
            if (isset($permissions['HINTMANAGER']))
                $hintManager = $permissions['HINTMANAGER'];
            if (isset($permissions['BROWSERMANAGER']))
                $browserManager = $permissions['BROWSERMANAGER'];
            if (isset($permissions['MONEYMANAGER']))
                $moneyManager = $permissions['MONEYMANAGER'];
            if (isset($permissions['ADDRENEWUSERBROWSER']))
                $addRenewUserBrowser = $permissions['ADDRENEWUSERBROWSER'];
            if (isset($permissions['CHECKINBROWSER']))
                $checkInBrowser = $permissions['CHECKINBROWSER'];
            if (isset($permissions['CASHREGISTERBROWSER']))
                $cashRegisterBrowser = $permissions['CASHREGISTERBROWSER'];
            if (isset($permissions['APIKEY']))
                $apiKey = $permissions['APIKEY'];
        }
        
        //Possible states
        $canDisplay = false;
        $hasToLogIn = false;
        $needsApiKey = false;
        
        if ($everyone)
            $canDisplay = true;
        
        //If a browser can display the page we check if the current browser has the required permissions.
        if (!$canDisplay && ($checkInBrowser || $addRenewUserBrowser || $cashRegisterBrowser)) {
            if (isset($_SESSION['Stippers']['browser'])) {
                if ($checkInBrowser && $_SESSION['Stippers']['browser']->canCheckIn)
                    $canDisplay = true;
                elseif ($addRenewUserBrowser && $_SESSION['Stippers']['browser']->canAddRenewUsers)
                    $canDisplay = true;
                elseif ($cashRegisterBrowser && $_SESSION['Stippers']['browser']->isCashRegister)
                    $canDisplay = true;
            }
        }
        
        //If certain users can display the page and someone is logged in we check if the logged in user has the required permissions.
        if (!$canDisplay && ($member || $userManager || $hintManager || $browserManager || $moneyManager || $admin)) {
            //No access yet and no user in session? Let user login!
            if (!isset($_SESSION['Stippers']['user']))
                $hasToLogIn = true;
            else {
                if ($member)
                    $canDisplay = true;
                else if ($userManager && $_SESSION['Stippers']['user']->isUserManager)
                    $canDisplay = true;
                else if ($hintManager && $_SESSION['Stippers']['user']->isHintManager)
                    $canDisplay = true;
                else if ($browserManager && $_SESSION['Stippers']['user']->isBrowserManager)
                    $canDisplay = true;
                else if ($browserManager && $_SESSION['Stippers']['user']->isMoneyManager)
                    $canDisplay = true;
                else if ($admin && $_SESSION['Stippers']['user']->isAdmin)
                    $canDisplay = true;
            }
        }
        
        //If requests with a valid api key can view the page we check if a valid key is given.
        if (!$canDisplay && substr_compare($requestData['requestedPage'], DomainConfig::API_PATH, 0, strlen(DomainConfig::API_PATH)) == 0 && $apiKey) {
            //Check if a key is given and it's in the list of valid keys
            if (isset($_GET['key']) && in_array($_GET['key'], APIConfig::VALID_KEYS))
                $canDisplay = true;
            else
                $needsApiKey = true;
        }
        
        if (!$canDisplay) {
            if ($needsApiKey) {
                header('HTTP/1.1 403 Forbidden');
                return false;
            }
            elseif ($hasToLogIn) {
                LoginController::get();
                return false;
            }
            elseif ($errorGettingBrowser || $errorGettingUser) {
                DBErrorController::get();
                return false;
            }
            else {
                AccessDeniedController::get();
                return false;
            }
        }
        else
            return true;
    }
}
