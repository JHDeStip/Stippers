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
        
        //If we have login in our post this means we are on the login page in and we must call the login controller.
        //The login controller will load
        if (isset($_POST['login']))
            LoginController::post();
        
        //We can now check the page access permissions.
        require_once __DIR__.'/../config/PageAccessPermissions.php';
        
        //Default to inaccessible for everyone
        $everyone = false;
        $member = false;
        $admin = false;
        $userManager = false;
        $hintManager = false;
        $browserManager = false;
        $addRenewUserBrowser = false;
        $checkInBrowser = false;

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
            if (isset($permissions['ADDRENEWUSERBROWSER']))
                $addRenewUserBrowser = $permissions['ADDRENEWUSERBROWSER'];
            if (isset($permissions['CHECKINBROWSER']))
                $checkInBrowser = $permissions['CHECKINBROWSER'];
        }
        
        //Possible states
        $canDisplay = false;
        $hasToLogIn = false;
        $browserDenied = false;
        $userDenied = false;
        
        if ($everyone)
            $canDisplay = true;
        
        //If a browser can display the page we get the data from the cookie
        //and check if the growser has the required permission.
        if (!$canDisplay && ($checkInBrowser || $addRenewUserBrowser)) {
            if (!isset($_COOKIE['stippersAuthorization']))
                $browserDenied = true;
            else {
                try {
                    $browser = BrowserDB::getBasicBrowserByUuid($_COOKIE['stippersAuthorization']);
                    
                    if ($checkInBrowser && $browser->canCheckIn)
                        $canDisplay = true;
                    else if ($browser && $browser->canAddRenewUsers)
                        $canDisplay = true;
                    else
                        $browserDenied = true;
                }
                catch(BrowserDBException $ex) {
                    if ($ex->getCode() != BrowserDBException::NOBROWSERFORUUID) {
                        DBErrorController::get();
                        return false;
                    }
                }
                catch(Exception $ex) {
                    DBErrorController::get();
                    return false;
                }
            }
        }
        
        //If certain users can display the page and someone is logged in
        //we renew the user and check if the user has the required permission.
        if (!$canDisplay && ($member || $userManager || $hintManager || $browserManager || $admin)) {
            if (!isset($_SESSION['Stippers']['user']))
                $hasToLogIn = true;
            else {
                try {
                    $newUser = UserDB::getBasicUserById($_SESSION['Stippers']['user']->userId);
                    
                    //If the user's password has changed we immediately log out!
                    if ($_SESSION['Stippers']['user']->passwordHash != $newUser->passwordHash) {
                        session_destroy();
                        ForcedLogoutController::get();
                        return false;
                    }
                    else {
                        $_SESSION['Stippers']['user'] = $newUser;
                        
                        if ($member)
                            $canDisplay = true;
                        else if ($userManager && $_SESSION['Stippers']['user']->isUserManager)
                            $canDisplay = true;
                        else if ($hintManager && $_SESSION['Stippers']['user']->isHintManager)
                            $canDisplay = true;
                        else if ($browserManager && $_SESSION['Stippers']['user']->isBrowserManager)
                            $canDisplay = true;
                        else if ($admin && $_SESSION['Stippers']['user']->isAdmin)
                            $canDisplay = true;
                        else
                            $userDenied = true;
                    }
                }
                catch(Exception $ex) {
                    DBErrorController::get();
                    return false;
                }
            }
        }

        if (!$canDisplay) {
            if ($hasToLogIn) {
                LoginController::get();
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
