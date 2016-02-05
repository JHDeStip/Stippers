/**
 * Created by Stan on 11/02/2015.
 */
var PASSWORDMINLENGTH = 8;
var PASSWORDMAXLENGTH = 1000;
var EMAILMAXLENGTH = 50;
var STRINGMAXLENGTH = 30;
var HOUSENUMBERMAXLENGTH = 4;
var POSTALCODEMINLENGTH = 4;
var POSTALCODEMAXLENGTH = 6;
var PHONEMAXLENGTH = 9;
var PHONEMAXLENGTH = 14;
var DATEMAXLENGTH = 10;
var MONEYMAXLENGTH = 6;
var YEARMAXLENGTH = 4;
var CARDNUMBERMAXLENGTH = 8;
var EMAILSUBJECTMAXLENGTH = 50;
var EMAILFILENAMEMAXLENGTH = 255;
var ADMINPERMISSIONMIN = 0;
var ADMINPERMISSIONMAX = 1;
var USERMANAGERPERMISSIONMIN = 0;
var USERMANAGERPERMISSIONMAX = 1;
var BROWSERMANAGERPERMISSIONMIN = 0;
var BROWSERMANAGERPERMISSIONMAX = 1;
var MONEYMANAGERPERMISSIONMIN = 0;
var MONEYMANAGERPERMISSIONMAX = 1;

var SUBMITBUTTON = 0;
var CANCELBUTTON = 1;
var ADDBUTTON = 2;
var EDITBUTTON = 3;
var DELETEBUTTON = 4;

var buttonClicked = SUBMITBUTTON;

function anyRadioChecked(radioName) {
    var radios = document.getElementsByName(radioName);
    for (var i=0; i<radios.length; i++){
        if (radios[i].checked) {
            return true;
        }
    }
    return false;
}

function checkDate(day, month, year) {
    return month > 0 && month < 13 && year > 0 && year < 32768 && day > 0 && day <= (new Date(year, month, 0)).getDate();
}