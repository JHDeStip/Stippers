<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class with methods to do safe math operations.
 */

abstract class SafeMath {

    /**
     * Returns 100 times the input as int, false on error.
     * Only input's with precision of 2 decimals or less are accepted.
     * 
     * @return int cents
     */
    public static function getCentsFromString($value) {
        //Cuts string on points and count parts
        $parts = explode('.', $value);
        $nParts = count($parts);
        
        //We can't have more than 1 comma
        if ($nParts > 2)
            return false;
        
        //Check if the precision isn't to high
        if (strlen(end($parts)) > 2)
            return false;
            
        
        //Check if each individual part is numeric
        foreach ($parts as $part)
            if (!is_numeric($part))
                return false;
        
        //Return given value times 100
        if ($nParts == 1)
            return $parts[0] * 100;
        else
            return $parts[0] * 100 + $parts[1];     
    }
}