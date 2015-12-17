<?php
/**
 * Created by PhpStorm.
 * User: Stan
 * Date: 15/01/2015
 * Time: 15:58
 */

class DatabaseException extends Exception{
    const CANNOTCONNECT = 1;

    public function __construct($message, $code = 0, $previous = null){
        parent::__construct($message, $code, $previous);
    }
}