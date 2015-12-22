<?php

/*
* The settings in this file are used to validate user input.
* They define the minimum and maximum lengts of data the user can enter throughout the application.
*/

abstract class DataValidationConfig {
    const PASSWORDMINLENGTH = 8;
    const PASSWORDMAXLENGTH = 1000;
    const EMAILMAXLENGTH = 50;
    
    const STRINGMAXLENGTH = 30;
    const HOUSENUMBERMAXLENGTH = 4;
    const POSTALCODEMINLENGTH = 4;
    const POSTALCODEMAXLENGTH = 6;
    const PHONEMAXLENGTH = 14;
    const DATEMAXLENGTH = 10;
    const BALANCEMAXLENGTH = 10;
    const YEARMAXLENGTH = 4;
    const CARDNUMBERMAXLENGTH = 8;
    
    const ADMINPERMISSIONMIN = 0;
    const ADMINPERMISSIONMAX = 1;
    const USERMANAGERPERMISSIONMIN = 0;
    const USERMANAGERPERMISSIONMAX = 1;
    const AUTHORIZEDBROWSERMANAGERPERMISSIONMIN = 0;
    const AUTHORIZEDBROWSERMANAGERPERMISSIONMAX = 1;
}