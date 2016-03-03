<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * The settings in this file define by who are what browser the individual pages are accessible.
 */

//Permissions for the add or renew user page
$_PERMISSIONS['home']['EVERYONE'] = true;
$_PERMISSIONS['home']['MEMBER'] = true;
$_PERMISSIONS['home']['ADMIN'] = true;
$_PERMISSIONS['home']['USERMANAGER'] = true;
$_PERMISSIONS['home']['BROWSERMANAGER'] = true;
$_PERMISSIONS['home']['MONEYMANAGER'] = true;
$_PERMISSIONS['home']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['home']['CHECKINBROWSER'] = true;
$_PERMISSIONS['home']['CASHREGISTERBROWSER'] = false;

//Permissions for the add or renew user page
$_PERMISSIONS['login']['EVERYONE'] = false;
$_PERMISSIONS['login']['MEMBER'] = true;
$_PERMISSIONS['login']['ADMIN'] = true;
$_PERMISSIONS['login']['USERMANAGER'] = true;
$_PERMISSIONS['login']['BROWSERMANAGER'] = true;
$_PERMISSIONS['login']['MONEYMANAGER'] = true;
$_PERMISSIONS['login']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['login']['CHECKINBROWSER'] = false;
$_PERMISSIONS['login']['CASHREGISTERBROWSER'] = false;

//Permissions for the profile page
$_PERMISSIONS['profile']['EVERYONE'] = false;
$_PERMISSIONS['profile']['MEMBER'] = true;
$_PERMISSIONS['profile']['ADMIN'] = true;
$_PERMISSIONS['profile']['USERMANAGER'] = true;
$_PERMISSIONS['profile']['BROWSERMANAGER'] = true;
$_PERMISSIONS['profile']['MONEYMANAGER'] = true;
$_PERMISSIONS['profile']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['profile']['CHECKINBROWSER'] = false;
$_PERMISSIONS['profile']['CASHREGISTERBROWSER'] = false;

//Permissions for the profile page
$_PERMISSIONS['changepassword']['EVERYONE'] = false;
$_PERMISSIONS['changepassword']['MEMBER'] = true;
$_PERMISSIONS['changepassword']['ADMIN'] = true;
$_PERMISSIONS['changepassword']['USERMANAGER'] = true;
$_PERMISSIONS['changepassword']['BROWSERMANAGER'] = true;
$_PERMISSIONS['changepassword']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['changepassword']['CHECKINBROWSER'] = false;
$_PERMISSIONS['changepassword']['CASHREGISTERBROWSER'] = false;

//Permissions for the my transactions page
$_PERMISSIONS['mytransactions']['EVERYONE'] = false;
$_PERMISSIONS['mytransactions']['MEMBER'] = true;
$_PERMISSIONS['mytransactions']['ADMIN'] = true;
$_PERMISSIONS['mytransactions']['USERMANAGER'] = true;
$_PERMISSIONS['mytransactions']['BROWSERMANAGER'] = true;
$_PERMISSIONS['mytransactions']['MONEYMANAGER'] = true;
$_PERMISSIONS['mytransactions']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['mytransactions']['CHECKINBROWSER'] = false;
$_PERMISSIONS['mytransactions']['CASHREGISTERBROWSER'] = false;

//Permissions for the add or renew user page
$_PERMISSIONS['addorrenewuser']['EVERYONE'] = false;
$_PERMISSIONS['addorrenewuser']['MEMBER'] = false;
$_PERMISSIONS['addorrenewuser']['ADMIN'] = true;
$_PERMISSIONS['addorrenewuser']['USERMANAGER'] = false;
$_PERMISSIONS['addorrenewuser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['addorrenewuser']['MONEYMANAGER'] = false;
$_PERMISSIONS['addorrenewuser']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['addorrenewuser']['CHECKINBROWSER'] = false;
$_PERMISSIONS['addorrenewuser']['CASHREGISTERBROWSER'] = false;


//Permissions for the add user page
$_PERMISSIONS['adduser']['EVERYONE'] = false;
$_PERMISSIONS['adduser']['MEMBER'] = false;
$_PERMISSIONS['adduser']['ADMIN'] = true;
$_PERMISSIONS['adduser']['USERMANAGER'] = false;
$_PERMISSIONS['adduser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['adduser']['MONEYMANAGER'] = false;
$_PERMISSIONS['adduser']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['adduser']['CHECKINBROWSER'] = false;
$_PERMISSIONS['adduser']['CASHREGISTERBROWSER'] = false;

//Permissions for the renew user page
$_PERMISSIONS['renewuser']['EVERYONE'] = false;
$_PERMISSIONS['renewuser']['MEMBER'] = false;
$_PERMISSIONS['renewuser']['ADMIN'] = true;
$_PERMISSIONS['renewuser']['USERMANAGER'] = false;
$_PERMISSIONS['renewuser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['renewuser']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['renewuser']['CHECKINBROWSER'] = false;
$_PERMISSIONS['renewuser']['CASHREGISTERBROWSER'] = false;

//Permissions for the renew user search page
$_PERMISSIONS['renewusersearch']['EVERYONE'] = false;
$_PERMISSIONS['renewusersearch']['MEMBER'] = false;
$_PERMISSIONS['renewusersearch']['ADMIN'] = true;
$_PERMISSIONS['renewusersearch']['USERMANAGER'] = false;
$_PERMISSIONS['renewusersearch']['BROWSERMANAGER'] = false;
$_PERMISSIONS['renewusersearch']['MONEYMANAGER'] = false;
$_PERMISSIONS['renewusersearch']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['renewusersearch']['CHECKINBROWSER'] = false;
$_PERMISSIONS['renewusersearch']['CASHREGISTERBROWSER'] = false;

//Permissions for the browser manager page
$_PERMISSIONS['managebrowser']['EVERYONE'] = false;
$_PERMISSIONS['managebrowser']['MEMBER'] = false;
$_PERMISSIONS['managebrowser']['ADMIN'] = true;
$_PERMISSIONS['managebrowser']['USERMANAGER'] = false;
$_PERMISSIONS['managebrowser']['BROWSERMANAGER'] = true;
$_PERMISSIONS['managebrowser']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['managebrowser']['CHECKINBROWSER'] = false;
$_PERMISSIONS['managebrowser']['CASHREGISTERBROWSER'] = false;

//Permissions for the add browser page
$_PERMISSIONS['addbrowser']['EVERYONE'] = false;
$_PERMISSIONS['addbrowser']['MEMBER'] = false;
$_PERMISSIONS['addbrowser']['ADMIN'] = true;
$_PERMISSIONS['addbrowser']['USERMANAGER'] = false;
$_PERMISSIONS['addbrowser']['BROWSERMANAGER'] = true;
$_PERMISSIONS['addbrowser']['MONEYMANAGER'] = false;
$_PERMISSIONS['addbrowser']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['addbrowser']['CHECKINBROWSER'] = false;
$_PERMISSIONS['addbrowser']['CASHREGISTERBROWSER'] = false;

//Permissions for the edit browser page
$_PERMISSIONS['editbrowser']['EVERYONE'] = false;
$_PERMISSIONS['editbrowser']['MEMBER'] = false;
$_PERMISSIONS['editbrowser']['ADMIN'] = true;
$_PERMISSIONS['editbrowser']['USERMANAGER'] = false;
$_PERMISSIONS['editbrowser']['BROWSERMANAGER'] = true;
$_PERMISSIONS['editbrowser']['MONEYMANAGER'] = false;
$_PERMISSIONS['editbrowser']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['editbrowser']['CHECKINBROWSER'] = false;

//Permissions for the change password page
$_PERMISSIONS['changepassword']['EVERYONE'] = false;
$_PERMISSIONS['changepassword']['MEMBER'] = true;
$_PERMISSIONS['changepassword']['ADMIN'] = true;
$_PERMISSIONS['changepassword']['USERMANAGER'] = true;
$_PERMISSIONS['changepassword']['BROWSERMANAGER'] = true;
$_PERMISSIONS['changepassword']['MONEYMANAGER'] = true;
$_PERMISSIONS['changepassword']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['changepassword']['CHECKINBROWSER'] = false;
$_PERMISSIONS['changepassword']['CASHREGISTERBROWSER'] = false;

//Permissions for the check in page
$_PERMISSIONS['checkin']['EVERYONE'] = false;
$_PERMISSIONS['checkin']['MEMBER'] = false;
$_PERMISSIONS['checkin']['ADMIN'] = true;
$_PERMISSIONS['checkin']['USERMANAGER'] = false;
$_PERMISSIONS['checkin']['BROWSERMANAGER'] = false;
$_PERMISSIONS['checkin']['MONEYMANAGER'] = false;
$_PERMISSIONS['checkin']['ADDRENEWUSERBROWSER'] = false;
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
$_PERMISSIONS['logout']['CASHREGISTERBROWSER'] = false;

//Permissions for the profile page
$_PERMISSIONS['profile']['EVERYONE'] = false;
$_PERMISSIONS['profile']['MEMBER'] = true;
$_PERMISSIONS['profile']['ADMIN'] = true;
$_PERMISSIONS['profile']['USERMANAGER'] = true;
$_PERMISSIONS['profile']['BROWSERMANAGER'] = true;
$_PERMISSIONS['profile']['MONEYMANAGER'] = true;
$_PERMISSIONS['profile']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['profile']['CHECKINBROWSER'] = false;
$_PERMISSIONS['profile']['CASHREGISTERBROWSER'] = false;

//Permissions for the reset password page
$_PERMISSIONS['resetpassword']['EVERYONE'] = true;
$_PERMISSIONS['resetpassword']['MEMBER'] = true;
$_PERMISSIONS['resetpassword']['ADMIN'] = true;
$_PERMISSIONS['resetpassword']['USERMANAGER'] = true;
$_PERMISSIONS['resetpassword']['BROWSERMANAGER'] = true;
$_PERMISSIONS['resetpassword']['MONEYMANAGER'] = true;
$_PERMISSIONS['resetpassword']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['resetpassword']['CHECKINBROWSER'] = true;
$_PERMISSIONS['resetpassword']['CASHREGISTERBROWSER'] = false;

//Permissions for the user manager page
$_PERMISSIONS['manageuser']['EVERYONE'] = false;
$_PERMISSIONS['manageuser']['MEMBER'] = false;
$_PERMISSIONS['manageuser']['ADMIN'] = true;
$_PERMISSIONS['manageuser']['USERMANAGER'] = true;
$_PERMISSIONS['manageuser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['manageuser']['MONEYMANAGER'] = false;
$_PERMISSIONS['manageuser']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['manageuser']['CHECKINBROWSER'] = false;
$_PERMISSIONS['manageuser']['CASHREGISTERBROWSER'] = false;

//Permissions for the edit user page
$_PERMISSIONS['edituser']['EVERYONE'] = false;
$_PERMISSIONS['edituser']['MEMBER'] = false;
$_PERMISSIONS['edituser']['ADMIN'] = true;
$_PERMISSIONS['edituser']['USERMANAGER'] = true;
$_PERMISSIONS['edituser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['edituser']['MONEYMANAGER'] = false;
$_PERMISSIONS['edituser']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['edituser']['CHECKINBROWSER'] = false;
$_PERMISSIONS['edituser']['CASHREGISTERBROWSER'] = false;

//Permissions for the user manager page
$_PERMISSIONS['downloadsearchresults']['EVERYONE'] = false;
$_PERMISSIONS['downloadsearchresults']['MEMBER'] = false;
$_PERMISSIONS['downloadsearchresults']['ADMIN'] = true;
$_PERMISSIONS['downloadsearchresults']['USERMANAGER'] = true;
$_PERMISSIONS['downloadsearchresults']['BROWSERMANAGER'] = false;
$_PERMISSIONS['downloadsearchresults']['MONEYMANAGER'] = false;
$_PERMISSIONS['downloadsearchresults']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['downloadsearchresults']['CHECKINBROWSER'] = false;
$_PERMISSIONS['downloadsearchresults']['CASHREGISTERBROWSER'] = false;

//Permissions for the user manager page
$_PERMISSIONS['manageusermoney']['EVERYONE'] = false;
$_PERMISSIONS['manageusermoney']['MEMBER'] = false;
$_PERMISSIONS['manageusermoney']['ADMIN'] = true;
$_PERMISSIONS['manageusermoney']['USERMANAGER'] = false;
$_PERMISSIONS['manageusermoney']['BROWSERMANAGER'] = false;
$_PERMISSIONS['manageusermoney']['MONEYMANAGER'] = true;
$_PERMISSIONS['manageusermoney']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['manageusermoney']['CHECKINBROWSER'] = false;
$_PERMISSIONS['manageusermoney']['CASHREGISTERBROWSER'] = false;

//Permissions for the user transactions page
$_PERMISSIONS['usertransactions']['EVERYONE'] = false;
$_PERMISSIONS['usertransactions']['MEMBER'] = false;
$_PERMISSIONS['usertransactions']['ADMIN'] = true;
$_PERMISSIONS['usertransactions']['USERMANAGER'] = true;
$_PERMISSIONS['usertransactions']['BROWSERMANAGER'] = false;
$_PERMISSIONS['usertransactions']['MONEYMANAGER'] = false;
$_PERMISSIONS['usertransactions']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['usertransactions']['CHECKINBROWSER'] = false;
$_PERMISSIONS['usertransactions']['CASHREGISTERBROWSER'] = false;

//Permissions for the all transactions page
$_PERMISSIONS['alltransactions']['EVERYONE'] = false;
$_PERMISSIONS['alltransactions']['MEMBER'] = false;
$_PERMISSIONS['alltransactions']['ADMIN'] = true;
$_PERMISSIONS['alltransactions']['USERMANAGER'] = true;
$_PERMISSIONS['alltransactions']['BROWSERMANAGER'] = false;
$_PERMISSIONS['alltransactions']['MONEYMANAGER'] = true;
$_PERMISSIONS['alltransactions']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['alltransactions']['CHECKINBROWSER'] = false;
$_PERMISSIONS['alltransactions']['CASHREGISTERBROWSER'] = false;

//Permissions for the weekly winner page
$_PERMISSIONS['weeklywinner']['EVERYONE'] = false;
$_PERMISSIONS['weeklywinner']['MEMBER'] = false;
$_PERMISSIONS['weeklywinner']['ADMIN'] = true;
$_PERMISSIONS['weeklywinner']['USERMANAGER'] = true;
$_PERMISSIONS['weeklywinner']['BROWSERMANAGER'] = false;
$_PERMISSIONS['weeklywinner']['MONEYMANAGER'] = false;
$_PERMISSIONS['weeklywinner']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['weeklywinner']['CHECKINBROWSER'] = false;
$_PERMISSIONS['weeklywinner']['CASHREGISTERBROWSER'] = false;

//Permissions for the email management page
$_PERMISSIONS['manageemail']['EVERYONE'] = false;
$_PERMISSIONS['manageemail']['MEMBER'] = false;
$_PERMISSIONS['manageemail']['ADMIN'] = true;
$_PERMISSIONS['manageemail']['USERMANAGER'] = true;
$_PERMISSIONS['manageemail']['BROWSERMANAGER'] = false;
$_PERMISSIONS['manageemail']['MONEYMANAGER'] = false;
$_PERMISSIONS['manageemail']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['manageemail']['CHECKINBROWSER'] = false;
$_PERMISSIONS['manageemail']['CASHREGISTERBROWSER'] = false;

//Permissions for the send email to users page
$_PERMISSIONS['sendemailtousers']['EVERYONE'] = false;
$_PERMISSIONS['sendemailtousers']['MEMBER'] = false;
$_PERMISSIONS['sendemailtousers']['ADMIN'] = true;
$_PERMISSIONS['sendemailtousers']['USERMANAGER'] = true;
$_PERMISSIONS['sendemailtousers']['BROWSERMANAGER'] = false;
$_PERMISSIONS['sendemailtousers']['MONEYMANAGER'] = false;
$_PERMISSIONS['sendemailtousers']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['sendemailtousers']['CHECKINBROWSER'] = false;
$_PERMISSIONS['sendemailtousers']['CASHREGISTERBROWSER'] = false;

//Permissions for the edit email page
$_PERMISSIONS['editemail']['EVERYONE'] = false;
$_PERMISSIONS['editemail']['MEMBER'] = false;
$_PERMISSIONS['editemail']['ADMIN'] = true;
$_PERMISSIONS['editemail']['USERMANAGER'] = true;
$_PERMISSIONS['editemail']['BROWSERMANAGER'] = false;
$_PERMISSIONS['editemail']['MONEYMANAGER'] = false;
$_PERMISSIONS['editemail']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['editemail']['CHECKINBROWSER'] = false;
$_PERMISSIONS['editemail']['CASHREGISTERBROWSER'] = false;

//Permissions for the cash register page
$_PERMISSIONS['cashregister']['EVERYONE'] = false;
$_PERMISSIONS['cashregister']['MEMBER'] = false;
$_PERMISSIONS['cashregister']['ADMIN'] = false;
$_PERMISSIONS['cashregister']['USERMANAGER'] = false;
$_PERMISSIONS['cashregister']['BROWSERMANAGER'] = false;
$_PERMISSIONS['cashregister']['MONEYMANAGER'] = false;
$_PERMISSIONS['cashregister']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['cashregister']['CHECKINBROWSER'] = false;
$_PERMISSIONS['cashregister']['CASHREGISTERBROWSER'] = true;

//Permissions for the chat page
$_PERMISSIONS['chat']['EVERYONE'] = false;
$_PERMISSIONS['chat']['MEMBER'] = true;
$_PERMISSIONS['chat']['ADMIN'] = true;
$_PERMISSIONS['chat']['USERMANAGER'] = true;
$_PERMISSIONS['chat']['BROWSERMANAGER'] = true;
$_PERMISSIONS['chat']['MONEYMANAGER'] = true;
$_PERMISSIONS['chat']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['chat']['CHECKINBROWSER'] = false;
$_PERMISSIONS['chat']['CASHREGISTERBROWSER'] = false;

//Permissions for the chat messages page
$_PERMISSIONS['chatmessages']['EVERYONE'] = false;
$_PERMISSIONS['chatmessages']['MEMBER'] = true;
$_PERMISSIONS['chatmessages']['ADMIN'] = true;
$_PERMISSIONS['chatmessages']['USERMANAGER'] = true;
$_PERMISSIONS['chatmessages']['BROWSERMANAGER'] = true;
$_PERMISSIONS['chatmessages']['MONEYMANAGER'] = true;
$_PERMISSIONS['chatmessages']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['chatmessages']['CHECKINBROWSER'] = false;
$_PERMISSIONS['chatmessages']['CASHREGISTERBROWSER'] = false;

//Permissions for the meat wheel page
$_PERMISSIONS['meatwheel']['EVERYONE'] = false;
$_PERMISSIONS['meatwheel']['MEMBER'] = true;
$_PERMISSIONS['meatwheel']['ADMIN'] = true;
$_PERMISSIONS['meatwheel']['USERMANAGER'] = true;
$_PERMISSIONS['meatwheel']['BROWSERMANAGER'] = true;
$_PERMISSIONS['meatwheel']['MONEYMANAGER'] = true;
$_PERMISSIONS['meatwheel']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['meatwheel']['CHECKINBROWSER'] = false;
$_PERMISSIONS['meatwheel']['CASHREGISTERBROWSER'] = false;

//Permissions for the page not found page
$_PERMISSIONS['pagenotfound']['EVERYONE'] = true;
$_PERMISSIONS['pagenotfound']['MEMBER'] = true;
$_PERMISSIONS['pagenotfound']['ADMIN'] = true;
$_PERMISSIONS['pagenotfound']['USERMANAGER'] = true;
$_PERMISSIONS['pagenotfound']['BROWSERMANAGER'] = true;
$_PERMISSIONS['pagenotfound']['MONEYMANAGER'] = true;
$_PERMISSIONS['pagenotfound']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['pagenotfound']['CHECKINBROWSER'] = true;
$_PERMISSIONS['pagenotfound']['CASHREGISTERBROWSER'] = false;
