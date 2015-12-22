<?php

require_once 'config/DomainConfig.php';

$requestData['requestedPage'] = explode('?', str_replace(DomainConfig::DOMAINSUFFIX, '', strtolower($_SERVER['REQUEST_URI'])), 2)[0];
$middleware = array();

//require_once 'middleware/SessionCleanup.php';
require_once 'middleware/Authorization.php';
//array_push($middleware, 'SessionCleanup');
array_push($middleware, 'Authorization');

//Aliases
switch ($requestData['requestedPage'])
{
    case '':
        $requestData['requestedPage'] = 'home';
        break;
}

//Assign controllers
$pageNotFound = false;
switch ($requestData['requestedPage'])
{
    case 'home':
        require_once('controllers/home/HomeController.php');
        $controller = 'HomeController';
        break;
	case 'manageuser':
        require_once('controllers/manageUser/ManageUserController.php');
        $controller = 'ManageUserController';
        break;
    case 'logout':
        require_once('controllers/authorization/LogoutController.php');
        $controller = 'LogoutController';
        break;
    default:
        $pageNotFound = true;
        require_once('controllers/pageNotFound/PageNotFoundController.php');
        $controller = 'PageNotFoundController';
        $requestData['requestedPage'] = 'pagenotfound';
        break;
}

$mwPass = true;
    for ($i = 0; $i < count($middleware) && $mwPass; $i++)
    {
        $mwPass = $middleware[$i]::run($requestData);
    }
    
if ($mwPass)
{
    if($_SERVER['REQUEST_METHOD'] === 'POST')
        $controller::post();
    else
        $controller::get();
}