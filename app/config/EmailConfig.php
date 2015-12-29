<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains email settings for Stippers.
 */

abstract class EmailConfig {
    //This is the directory where email files will be stored and retreived from
    const EMAILFILESDIR = 'emailFiles';
    
    const MAXFILESIZE = 1048576;
}
