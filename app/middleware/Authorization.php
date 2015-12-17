<?php

require_once "IMiddleware.php";

require_once __DIR__."/../controllers/authorization/LoginController.php";
require_once __DIR__."/../controllers/authorization/DBErrorController.php";
require_once __DIR__."/../controllers/authorization/ForcedLogoutController.php";
require_once __DIR__."/../controllers/authorization/AccessDeniedController.php";

require_once __DIR__."/../models/browserAuthorization/AuthorizedBrowser.php";
require_once __DIR__."/../models/browserAuthorization/AuthorizedBrowserDB.php";
require_once __DIR__."/../models/browserAuthorization/AuthorizedBrowserDBException.php";

require_once __DIR__."/../models/user/User.php";
require_once __DIR__."/../models/user/UserDB.php";
require_once __DIR__."/../models/user/UserDBException.php";
require_once __DIR__."/../models/user/UserDBException.php";

abstract class Authorization implements IMiddleware {
    
    public static function run(array $requestData) {
        
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        
        if (isset($_POST["login"]))
            LoginController::post();
                
        require_once __DIR__."/../config/PageAccessPermissions.php";
        
        $everyone = false;
        $member = false;
        $admin = false;
        $userManager = false;
        $hintManager = false;
        $authorizedBrowserManager = false;
        $addRenewUserBrowser = false;
        $checkInBrowser = false;

        if (isset($_PERMISSIONS[$requestData["requestedPage"]])) {
            $permissions = $_PERMISSIONS[$requestData["requestedPage"]];
            
            if (isset($permissions["EVERYONE"]))
                $everyone = $permissions["EVERYONE"];
            if (isset($permissions["MEMBER"]))
                $member = $permissions["MEMBER"];
            if (isset($permissions["ADMIN"]))
                $admin = $permissions["ADMIN"];
            if (isset($permissions["USERMANAGER"]))
                $userManager = $permissions["USERMANAGER"];
            if (isset($permissions["HINTMANAGER"]))
                $hintManager = $permissions["HINTMANAGER"];
            if (isset($permissions["AUTHORIZEDBROWSERMANAGER"]))
                $authorizedBrowserManager = $permissions["AUTHORIZEDBROWSERMANAGER"];
            if (isset($permissions["ADDRENEWUSERBROWSER"]))
                $addRenewUserBrowser = $permissions["ADDRENEWUSERBROWSER"];
            if (isset($permissions["CHECKINBROWSER"]))
                $checkInBrowser = $permissions["CHECKINBROWSER"];
        }
        
        $canDisplay = false;
        $hasToLogIn = false;
        $browserDenied = false;
        $userDenied = false;
        
        if ($everyone)
            $canDisplay = true;
        
        if (!$canDisplay && ($checkInBrowser || $addRenewUserBrowser)) {
            if (!isset($_COOKIE["stippersAuthorization"]))
                $browserDenied = true;
            else {
                try {
                    $authorizedBrowser = AuthorizedBrowserDB::getBasicAuthorizedBrowser($_COOKIE["stippersAuthorization"]);
                    
                    if ($checkInBrowser && $authorizedBrowser->canCheckIn)
                        $canDisplay = true;
                    else if ($authorizedBrowser && $authorizedBrowser->canAddUpdateUsers)
                        $canDisplay = true;
                    else
                        $BrowserDenied = true;
                }
                catch(Exception $ex) {
                    DBErrorController::get();
                    return false;
                }
            }
        }
        
        if (!$canDisplay && ($member || $userManager || $hintManager || $authorizedBrowserManager || $admin)) {
            if (!isset($_SESSION["stippersUser"]))
                $hasToLogIn = true;
            else {
                try {
                    $newUser = UserDB::getBasicUserById($_SESSION["stippersUser"]->userId);
                    
                    if ($_SESSION["stippersUser"]->passwordHash != $newUser->passwordHash) {
                        session_destroy();
                        ForcedLogoutController::get();
                        return false;
                    }
                    else {
                        $_SESSION["stippersUser"] = $newUser;
                        
                        if ($member)
                            $canDisplay = true;
                        else if ($userManager && $_SESSION["stippersUser"]->isUserManager)
                            $canDisplay = true;
                        else if ($hintManager && $_SESSION["stippersUser"]->isHintManager)
                            $canDisplay = true;
                        else if ($authorizedBrowserManager && $_SESSION["stippersUser"]->isAuthorizedBrowserManager)
                            $canDisplay = true;
                        else if ($admin && $_SESSION["stippersUser"]->isAdmin)
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
