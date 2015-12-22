<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Authorized Computer Database exception class.
 */

class AuthorizedBrowserDBException extends Exception{
    const UNKNOWNERROR = 1;
    const BROWSERNAMEEXISTS = 2;
    const BROWSERNOTFOUND = 3;
    const BROWSEROUTOFDATE = 4;

    public function __construct($message, $code = 0, $previous = null){
        parent::__construct($message, $code, $previous);
    }
} 