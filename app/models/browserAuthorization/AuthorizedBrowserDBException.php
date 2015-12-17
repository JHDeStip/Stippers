<?php
/**
 * Created by PhpStorm.
 * User: Stan
 * Date: 27/11/2014
 * Time: 21:05
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