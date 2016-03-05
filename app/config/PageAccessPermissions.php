<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * The settings in this file define by who are what browser the individual pages are accessible.
 */

//Permissions for the home page
$_PERMISSIONS['home']['EVERYONE'] = true;
$_PERMISSIONS['home']['MEMBER'] = true;
$_PERMISSIONS['home']['ADMIN'] = true;
$_PERMISSIONS['home']['USERMANAGER'] = true;
$_PERMISSIONS['home']['BROWSERMANAGER'] = true;
$_PERMISSIONS['home']['MONEYMANAGER'] = true;
$_PERMISSIONS['home']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['home']['CHECKINBROWSER'] = true;
$_PERMISSIONS['home']['CASHREGISTERBROWSER'] = true;

//Permissions for the login page
$_PERMISSIONS['login']['MEMBER'] = true;
$_PERMISSIONS['login']['ADMIN'] = true;
$_PERMISSIONS['login']['USERMANAGER'] = true;
$_PERMISSIONS['login']['BROWSERMANAGER'] = true;
$_PERMISSIONS['login']['MONEYMANAGER'] = true;

//Permissions for the profile page
$_PERMISSIONS['profile']['MEMBER'] = true;
$_PERMISSIONS['profile']['ADMIN'] = true;
$_PERMISSIONS['profile']['USERMANAGER'] = true;
$_PERMISSIONS['profile']['BROWSERMANAGER'] = true;
$_PERMISSIONS['profile']['MONEYMANAGER'] = true;

//Permissions for the change password page
$_PERMISSIONS['changepassword']['MEMBER'] = true;
$_PERMISSIONS['changepassword']['ADMIN'] = true;
$_PERMISSIONS['changepassword']['USERMANAGER'] = true;
$_PERMISSIONS['changepassword']['BROWSERMANAGER'] = true;

//Permissions for the my transactions page
$_PERMISSIONS['mytransactions']['MEMBER'] = true;
$_PERMISSIONS['mytransactions']['ADMIN'] = true;
$_PERMISSIONS['mytransactions']['USERMANAGER'] = true;
$_PERMISSIONS['mytransactions']['BROWSERMANAGER'] = true;
$_PERMISSIONS['mytransactions']['MONEYMANAGER'] = true;

//Permissions for the add or renew user page
$_PERMISSIONS['addorrenewuser']['ADMIN'] = true;
$_PERMISSIONS['addorrenewuser']['ADDRENEWUSERBROWSER'] = true;

//Permissions for the add user page
$_PERMISSIONS['adduser']['ADMIN'] = true;
$_PERMISSIONS['adduser']['ADDRENEWUSERBROWSER'] = true;

//Permissions for the renew user page
$_PERMISSIONS['renewuser']['ADMIN'] = true;
$_PERMISSIONS['renewuser']['ADDRENEWUSERBROWSER'] = true;

//Permissions for the renew user search page
$_PERMISSIONS['renewusersearch']['ADMIN'] = true;
$_PERMISSIONS['renewusersearch']['ADDRENEWUSERBROWSER'] = true;

//Permissions for the browser manager page
$_PERMISSIONS['managebrowser']['ADMIN'] = true;
$_PERMISSIONS['managebrowser']['BROWSERMANAGER'] = true;

//Permissions for the add browser page
$_PERMISSIONS['addbrowser']['ADMIN'] = true;
$_PERMISSIONS['addbrowser']['BROWSERMANAGER'] = true;

//Permissions for the edit browser page
$_PERMISSIONS['editbrowser']['ADMIN'] = true;
$_PERMISSIONS['editbrowser']['BROWSERMANAGER'] = true;

//Permissions for the check in page
$_PERMISSIONS['checkin']['ADMIN'] = true;
$_PERMISSIONS['checkin']['CHECKINBROWSER'] = true;

//Permissions for the logout page
$_PERMISSIONS['logout']['EVERYONE'] = true;
$_PERMISSIONS['logout']['MEMBER'] = true;
$_PERMISSIONS['logout']['ADMIN'] = true;
$_PERMISSIONS['logout']['USERMANAGER'] = true;
$_PERMISSIONS['logout']['BROWSERMANAGER'] = true;
$_PERMISSIONS['logout']['MONEYMANAGER'] = true;
$_PERMISSIONS['logout']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['logout']['CHECKINBROWSER'] = true;

//Permissions for the reset password page
$_PERMISSIONS['resetpassword']['EVERYONE'] = true;
$_PERMISSIONS['resetpassword']['MEMBER'] = true;
$_PERMISSIONS['resetpassword']['ADMIN'] = true;
$_PERMISSIONS['resetpassword']['USERMANAGER'] = true;
$_PERMISSIONS['resetpassword']['BROWSERMANAGER'] = true;
$_PERMISSIONS['resetpassword']['MONEYMANAGER'] = true;
$_PERMISSIONS['resetpassword']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['resetpassword']['CHECKINBROWSER'] = true;

//Permissions for the user manager page
$_PERMISSIONS['manageuser']['ADMIN'] = true;
$_PERMISSIONS['manageuser']['USERMANAGER'] = true;

//Permissions for the edit user page
$_PERMISSIONS['edituser']['ADMIN'] = true;
$_PERMISSIONS['edituser']['USERMANAGER'] = true;

//Permissions for the user manager page
$_PERMISSIONS['downloadsearchresults']['ADMIN'] = true;
$_PERMISSIONS['downloadsearchresults']['USERMANAGER'] = true;

//Permissions for the user manager page
$_PERMISSIONS['manageusermoney']['ADMIN'] = true;
$_PERMISSIONS['manageusermoney']['MONEYMANAGER'] = true;

//Permissions for the user transactions page
$_PERMISSIONS['usertransactions']['ADMIN'] = true;
$_PERMISSIONS['usertransactions']['USERMANAGER'] = true;

//Permissions for the all transactions page
$_PERMISSIONS['alltransactions']['ADMIN'] = true;
$_PERMISSIONS['alltransactions']['USERMANAGER'] = true;
$_PERMISSIONS['alltransactions']['MONEYMANAGER'] = true;

//Permissions for the weekly winner page
$_PERMISSIONS['weeklywinner']['ADMIN'] = true;
$_PERMISSIONS['weeklywinner']['USERMANAGER'] = true;

//Permissions for the email management page
$_PERMISSIONS['manageemail']['ADMIN'] = true;
$_PERMISSIONS['manageemail']['USERMANAGER'] = true;

//Permissions for the send email to users page
$_PERMISSIONS['sendemailtousers']['ADMIN'] = true;
$_PERMISSIONS['sendemailtousers']['USERMANAGER'] = true;

//Permissions for the edit email page
$_PERMISSIONS['editemail']['ADMIN'] = true;
$_PERMISSIONS['editemail']['USERMANAGER'] = true;

//Permissions for the cash register page
$_PERMISSIONS['cashregister']['CASHREGISTERBROWSER'] = true;

//Permissions for the chat page
$_PERMISSIONS['chat']['MEMBER'] = true;
$_PERMISSIONS['chat']['ADMIN'] = true;
$_PERMISSIONS['chat']['USERMANAGER'] = true;
$_PERMISSIONS['chat']['BROWSERMANAGER'] = true;
$_PERMISSIONS['chat']['MONEYMANAGER'] = true;

//Permissions for the chat messages page
$_PERMISSIONS['chatmessages']['MEMBER'] = true;
$_PERMISSIONS['chatmessages']['ADMIN'] = true;
$_PERMISSIONS['chatmessages']['USERMANAGER'] = true;
$_PERMISSIONS['chatmessages']['BROWSERMANAGER'] = true;
$_PERMISSIONS['chatmessages']['MONEYMANAGER'] = true;

//Permissions for the meat wheel page
$_PERMISSIONS['meatwheel']['MEMBER'] = true;
$_PERMISSIONS['meatwheel']['ADMIN'] = true;
$_PERMISSIONS['meatwheel']['USERMANAGER'] = true;
$_PERMISSIONS['meatwheel']['BROWSERMANAGER'] = true;
$_PERMISSIONS['meatwheel']['MONEYMANAGER'] = true;

//Permissions for the page not found page
$_PERMISSIONS['pagenotfound']['EVERYONE'] = true;
$_PERMISSIONS['pagenotfound']['MEMBER'] = true;
$_PERMISSIONS['pagenotfound']['ADMIN'] = true;
$_PERMISSIONS['pagenotfound']['USERMANAGER'] = true;
$_PERMISSIONS['pagenotfound']['BROWSERMANAGER'] = true;
$_PERMISSIONS['pagenotfound']['MONEYMANAGER'] = true;
$_PERMISSIONS['pagenotfound']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['pagenotfound']['CHECKINBROWSER'] = true;
$_PERMISSIONS['pagenotfound']['CASHREGISTERBROWSER'] = true;

//Permissions for check in api calls
$_PERMISSIONS['api/checkin']['APIKEY'] = true;