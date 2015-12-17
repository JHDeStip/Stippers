<?php

require_once __DIR__."/../IController.php";
require_once __DIR__."/../../helperClasses/View.php";

require_once __DIR__."/../../config/SecurityConfig.php";

require_once __DIR__."/../../models/user/User.php";
require_once __DIR__."/../../models/user/UserDB.php";
require_once __DIR__."/../../models/user/UserDBException.php";

require_once "LoginViewValidator.php";

abstract class LoginController implements IController
{
    public static function get()
    {
        $data["title"] = "Login";
        $data["LoginView"]["login_formAction"] = $_SERVER["REQUEST_URI"];
        $data["LoginView"]["email"] = "";
        $data["LoginView"]["errMsgs"] = LoginController::initErrMsgs();
        View::showBasicView(["authorization/LoginView"], $data);
    }
    
    public static function post()
    {
        $errMsgs = LoginViewValidator::validate($_POST);
        
        if (is_null($errMsgs))
        {
            try
            {
                $passwordSalt = UserDB::getPasswordSaltByEmail($_POST["email"]);
                $passwordHash = hash_pbkdf2("sha256", $_POST["password"], $passwordSalt, SecurityConfig::NPASSWORDHASHITERATIONS);
                $_SESSION["stippersUser"] = UserDB::getBasicUserByEmailPasswordHash($_POST["email"], $passwordHash);
                
                /*
                At this point we have a POST request with data from the login form. Because of that the router will try to run 'POST'
                on the controller of the requested page. This is incorrect and instead it should 'GET' the requested page.
                By forcing the REQUEST_METHOD to GET we trick the router into calling 'GET' on the controller.
                */
                $_SERVER["REQUEST_METHOD"] = "GET";
            } catch (Exception $ex) {
                if (is_a($ex, "UserDBException")) {
                    $data["title"] = "Login";
                    $data["LoginView"]["login_formAction"] = $_SERVER["REQUEST_URI"];
                    $data["LoginView"]["email"] = $_POST["email"];
                     
                    if ($ex->getCode() == UserDBException::NOUSERFOREMAILPASSWORD || $ex->getCode() == UserDBException::NOUSERFOREMAIL)
                        $data["LoginView"]["errMsgs"]["global"] = '<h2 class="error_message" id="login_form_error_message">E-mailadres en/of wachtwoord onjuist.</h2>';
                    else
                        $data["LoginView"]["errMsgs"]["global"] = '<h2 class="error_message" id="login_form_error_message">Kan niet aanmelden, probeer het opnieuw.</h2>';
                    
                    View::showBasicView(["authorization/LoginView"], $data);
                }
            }
        }
        else
        {
            $data["title"] = "Login";
            $data["LoginView"]["login_formAction"] = $_SERVER["REQUEST_URI"];
            $data["LoginView"]["email"] = $_POST["email"];
            $data["LoginView"]["errMsgs"] = LoginController::initErrMsgs();
            if (isset($errMsgs["global"]));
                $data["LoginView"]["errMsgs"]["global"] = $errMsgs["global"];
            View::showBasicView(["authorization/LoginView"], $data);
        }
    }
    
    private static function initErrMsgs()
    {
        $errMsgs["global"] = "";
        return $errMsgs;
    }
}
