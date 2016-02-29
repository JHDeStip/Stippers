/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains global javascript code.
 */

var PASSWORD_MIN_LENGTH = 8;
var PASSWORD_MAX_LENGTH = 1000;
var EMAIL_MAX_LENGTH = 50;
var STRING_MAX_LENGTH = 30;
var HOUSE_NUMBER_MAX_LENGTH = 4;
var POSTAL_CODE_MIN_LENGTH = 4;
var POSTAL_CODE_MAX_LENGTH = 6;
var PHONE_MAX_LENGTH = 9;
var PHONE_MAX_LENGTH = 14;
var DATE_MAX_LENGTH = 10;
var MONEY_MAX_LENGTH = 6;
var YEAR_MAX_LENGTH = 4;
var CARD_NUMBER_MAX_LENGTH = 8;
var EMAIL_SUBJECT_MAX_LENGTH = 50;
var EMAIL_FILE_NAME_MAX_LENGTH = 255;
var EMAIL_FILE_MAX_SIZE = 1048576;
var ADMIN_PERMISSION_MIN = 0;
var ADMIN_PERMISSION_MAX = 1;
var USER_MANAGER_PERMISSION_MIN = 0;
var USER_MANAGER_PERMISSION_MAX = 1;
var BROWSER_MANAGER_PERMISSION_MIN = 0;
var BROWSER_MANAGER_PERMISSION_MAX = 1;
var MONEY_MANAGER_PERMISSION_MIN = 0;
var MONEY_MANAGER_PERMISSION_MAX = 1;
var CHAT_MESSAGE_MAX_LENGTH = 150;

var SUBMIT_BUTTON = 0;
var CANCEL_BUTTON = 1;
var ADD_BUTTON = 2;
var EDIT_BUTTON = 3;
var DELETE_BUTTON = 4;
var BACK_BUTTON = 5;

var buttonClicked = SUBMIT_BUTTON;

function checkDate(day, month, year) {
    return month > 0 && month < 13 && year > 0 && year < 32768 && day > 0 && day <= (new Date(year, month, 0)).getDate();
}