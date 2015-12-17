<?php

require_once "config/DomainConfig.php";

$uri = strtoupper($_SERVER["REQUEST_URI"]);
$requestData["requestedPage"] = str_replace(DomainConfig::DOMAINSUFFIX, "", $uri);

$middleware = array();

//require_once "middleware/SessionCleanup.php";
require_once "middleware/Authorization.php";
//array_push($middleware, "SessionCleanup");
array_push($middleware, "Authorization");

//Aliases
switch ($requestData["requestedPage"])
{
    case "":
        $requestData["requestedPage"] = "HOME";
        break;
}

$pageNotFound = false;
switch ($requestData["requestedPage"])
{
    case "HOME":
        require_once("controllers/home/HomeController.php");
        $controller = 'HomeController';
        break;
    case "LOGOUT":
        require_once("controllers/authorization/LogoutController.php");
        $controller = 'LogoutController';
        break;
    default:
        $pageNotFound = true;
        require_once("controllers/pageNotFound/PageNotFoundController.php");
        $controller = 'PageNotFoundController';
        break;
}

$mwPass = true;
if (!$pageNotFound)
{
    for ($i = 0; $i < count($middleware) && $mwPass; $i++)
    {
        $mwPass = $middleware[$i]::run($requestData);
    }
}
    
if ($mwPass)
{
    if($_SERVER["REQUEST_METHOD"] === "POST")
        $controller::post();
    else
        $controller::get();
}