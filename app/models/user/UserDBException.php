<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * User Database exception class.
 */

class UserDBException extends Exception {
    const UNKNOWNERROR = 1;
    const EMAILALREADYEXISTS = 2;
    const CARDALREADYUSED = 3;
    const NOUSERFORID = 4;
    const USERALREADYMEMBER = 5;
    const CANNOTGETNEXTUSERID = 6;
    const NOUSERFOREMAIL = 7;
    const USEROUTOFDATE = 8;
    const CANNOTPREPARESTMT = 9;

    public function __construct($message, $code = 0, $previous = null){
        parent::__construct($message, $code, $previous);
    }
}