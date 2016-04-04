<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Email exception class.
 */

class EmailException extends Exception {
    const UNKNOWNERROR = 1;
    const CANNOTREADEMAILFILE = 2;
    
    public function __construct($message, $code = 0, $previous = null){
        parent::__construct($message, $code, $previous);
    }
} 