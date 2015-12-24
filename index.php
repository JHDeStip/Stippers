<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Website entry. This is the index page which will include the router.
 */

require_once 'app/helperClasses/error/ErrorHandler.php';

//Register errorhandler for fatal errors (PHP crash)
register_shutdown_function("ErrorHandler::fatalErrorHandler");
//Don't show PHP error messages (we use our own handler to show a message)
//error_reporting(0);

header('Content-Type: text/html; charset=utf-8');

require_once 'app/router.php';