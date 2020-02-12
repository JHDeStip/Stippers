<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * The settings in this file are used to validate user input.
 */

abstract class DataValidationConfig {
    const PASSWORD_MIN_LENGTH = 8;
    const PASSWORD_MAX_LENGTH = 1000;
    const EMAIL_MAX_LENGTH = 50;
    
    const STRING_MAX_LENGTH = 30;
    const HOUSE_NUMBER_MAX_LENGTH = 4;
    const POSTAL_CODE_MIN_LENGTH = 4;
    const POSTAL_CODE_MAX_LENGTH = 6;
    const PHONE_MIN_LENGTH = 9;
    const PHONE_MAX_LENGTH = 14;
    const DATE_MAX_LENGTH = 10;
    const MONEY_MAX_LENGTH = 6;
    const YEAR_MAX_LENGTH = 4;
    const CARD_NUMBER_MAX_LENGTH = 8;
	const CHECK_IN_MESSAGE_MAX_LENGTH = 50;
    
    const EMAIL_SUBJECT_MAX_LENGTH = 50;
    const EMAIL_FILE_NAME_MAX_LENGTH = 255;
    const EMAIL_FILE_MAX_SIZE = 1048576;
    
    const ADMIN_PERMISSION_MIN = 0;
    const ADMIN_PERMISSION_MAX = 1;
    const USER_MANAGER_PERMISSION_MIN = 0;
    const USER_MANAGER_PERMISSION_MAX = 1;
    const BROWSER_MANAGER_PERMISSION_MIN = 0;
    const BROWSER_MANAGER_PERMISSION_MAX = 1;
    const MONEY_MANAGER_PERMISSION_MIN = 0;
    const MONEY_MANAGER_PERMISSION_MAX = 1;
    
    const CHAT_MESSAGE_MAX_LENGTH = 150;
}