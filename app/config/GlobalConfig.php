<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains global settings for Stippers.
 */

abstract class GlobalConfig {
    //This is the timezone all times will be displayed for
    const MYSQL_TIME_ZONE = '+01:00';
    const PHP_TIME_ZONE = 'Europe/Brussels';
    
    //User ID of the admin account
    const ADMINID = 1;
}
