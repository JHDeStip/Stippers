<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * User Database exception class.
 */

class UserDBException extends Exception{
    const UNKNOWNERROR = 1;
    const EMAILALREADYEXISTS = 2;
    const CARDALREADYUSED = 3;
    const NOUSERFORCARDNUMER = 4;
    const NOUSERFOREMAILPASSWORD = 5;
    const NOUSERFORID = 6;
    const USERALREADYMEMBER = 7;
    const CANNOTGETNEXTUSERID = 8;
    const NOUSERFOREMAIL = 9;
    const USEROUTOFDATE = 10;


    public function __construct($message, $code = 0, $previous = null){
        parent::__construct($message, $code, $previous);
    }
} 