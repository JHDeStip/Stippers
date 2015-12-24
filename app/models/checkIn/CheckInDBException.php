<?php
/**
 * Created by PhpStorm.
 * User: Stan
 * Date: 27/11/2014
 * Time: 21:05
 */

class CheckInDBException extends Exception{
    const UNKNOWNERROR = 1;
    const ALREADYCHECKEDIN = 2;

    public function __construct($message, $code = 0, $previous = null){
        parent::__construct($message, $code, $previous);
    }
} 