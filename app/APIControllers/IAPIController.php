<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Interface for API controllers.
 */

interface IAPIController
{
    public static function get();
    
    public static function post();
    
    public static function put();
    
    public static function delete();
}
