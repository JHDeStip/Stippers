<?php
/**
 * Created by PhpStorm.
 * User: Stan
 * Date: 27/11/2014
 * Time: 20:31
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