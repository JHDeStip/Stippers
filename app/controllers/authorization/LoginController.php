<?php

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../config/SecurityConfig.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../views/authorization/LoginViewValidator.php';

abstract class LoginController implements IController {
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Login';
        $page->data['LoginView']['login_formAction'] = $_SERVER['REQUEST_URI'];
        $page->data['LoginView']['email'] = '';
        $page->data['LoginView']['errMsgs'] = LoginViewValidator::initErrMsgs();
        $page->addView('authorization/LoginView');
        $page->showBasic();
    }
    
    public static function post() {
        $errMsgs = LoginViewValidator::validate($_POST);
        if (empty($errMsgs)) {
            try {
                $passwordSalt = UserDB::getPasswordSaltByEmail($_POST['email']);
                $passwordHash = hash_pbkdf2("sha256", $_POST['password'], $passwordSalt, SecurityConfig::NPASSWORDHASHITERATIONS);
                $_SESSION['stippersUser'] = UserDB::getBasicUserByEmailPasswordHash($_POST['email'], $passwordHash);
                
                /*
                At this point we have a POST request with data from the login form. Because of that the router will try to run 'POST'
                on the controller of the requested page. This is incorrect and instead it should 'GET' the requested page.
                By forcing the REQUEST_METHOD to GET we trick the router into calling 'GET' on the controller.
                */
                $_SERVER['REQUEST_METHOD'] = 'GET';
                
                /*
                We're redirecting to another page, so we don't want the login details to be in post for that page.
                For example the user search pages will pre populate their fields with this data if we don't clear it.
                */
                unset($_POST);
            }
            catch (Exception $ex) {
                if (is_a($ex, 'UserDBException')) {
                    $page = new Page();
                    $page->data['title'] = 'Login';
                    $page->data['LoginView']['login_formAction'] = $_SERVER['REQUEST_URI'];
                    $page->data['LoginView']['email'] = $_POST['email'];
                     
                    if ($ex->getCode() == UserDBException::NOUSERFOREMAILPASSWORD || $ex->getCode() == UserDBException::NOUSERFOREMAIL)
                        $page->data['LoginView']['errMsgs']['global'] = '<h2 class="error_message" id="login_form_error_message">E-mailadres en/of wachtwoord onjuist.</h2>';
                    else
                        $page->data['LoginView']['errMsgs']['global'] = '<h2 class="error_message" id="login_form_error_message">Kan niet aanmelden, probeer het opnieuw.</h2>';
                    
                    $page->addView('authorization/LoginView');
                    $page->showBasic();
                }
            }
        }
        else {
            $page = new Page();
            $page->data['title'] = 'Login';
            $page->data['LoginView']['login_formAction'] = $_SERVER['REQUEST_URI'];
            $page->data['LoginView']['email'] = $_POST['email'];
            $page->data['LoginView']['errMsgs'] = LoginViewValidator::initErrMsgs();
            $page->data['LoginView']['errMsgs'] = array_merge($page->data['LoginView']['errMsgs'], $errMsgs);
            $page->addView("authorization/LoginView");
            $page->showBasic();
        }
    }
}
