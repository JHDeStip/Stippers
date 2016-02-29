<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Chat Database exception class.
 */

class ChatDBException extends Exception{
    const UNKNOWNERROR = 1;
    const CANNOTGETNEXTMESSAGEID = 2;
    const CANNOTPREPARESTMT = 3;

    public function __construct($message, $code = 0, $previous = null){
        parent::__construct($message, $code, $previous);
    }
}