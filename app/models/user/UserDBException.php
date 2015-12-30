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
    const NOUSERFORID = 5;
    const USERALREADYMEMBER = 6;
    const CANNOTGETNEXTUSERID = 7;
    const NOUSERFOREMAIL = 8;
    const USEROUTOFDATE = 9;


    public function __construct($message, $code = 0, $previous = null){
        parent::__construct($message, $code, $previous);
    }
} 