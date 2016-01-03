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
$_PERMISSIONS['home']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['home']['CHECKINBROWSER'] = true;
$_PERMISSIONS['home']['CASHREGISTERBROWSER'] = false;

//Permissions for the add or renew user page
$_PERMISSIONS['login']['EVERYONE'] = false;
$_PERMISSIONS['login']['MEMBER'] = true;
$_PERMISSIONS['login']['ADMIN'] = true;
$_PERMISSIONS['login']['USERMANAGER'] = true;
$_PERMISSIONS['login']['BROWSERMANAGER'] = true;
$_PERMISSIONS['login']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['login']['CHECKINBROWSER'] = false;
$_PERMISSIONS['login']['CASHREGISTERBROWSER'] = false;

//Permissions for the profile page
$_PERMISSIONS['profile']['EVERYONE'] = false;
$_PERMISSIONS['profile']['MEMBER'] = true;
$_PERMISSIONS['profile']['ADMIN'] = true;
$_PERMISSIONS['profile']['USERMANAGER'] = true;
$_PERMISSIONS['profile']['BROWSERMANAGER'] = true;
$_PERMISSIONS['profile']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['profile']['CHECKINBROWSER'] = false;

//Permissions for the profile page
$_PERMISSIONS['changepassword']['EVERYONE'] = false;
$_PERMISSIONS['changepassword']['MEMBER'] = true;
$_PERMISSIONS['changepassword']['ADMIN'] = true;
$_PERMISSIONS['changepassword']['USERMANAGER'] = true;
$_PERMISSIONS['changepassword']['BROWSERMANAGER'] = true;
$_PERMISSIONS['changepassword']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['changepassword']['CHECKINBROWSER'] = false;
$_PERMISSIONS['changepassword']['CASHREGISTERBROWSER'] = false;

//Permissions for the add or renew user page
$_PERMISSIONS['addorrenewuser']['EVERYONE'] = false;
$_PERMISSIONS['addorrenewuser']['MEMBER'] = false;
$_PERMISSIONS['addorrenewuser']['ADMIN'] = true;
$_PERMISSIONS['addorrenewuser']['USERMANAGER'] = false;
$_PERMISSIONS['addorrenewuser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['addorrenewuser']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['addorrenewuser']['CHECKINBROWSER'] = false;
$_PERMISSIONS['addorrenewuser']['CASHREGISTERBROWSER'] = false;


//Permissions for the add user page
$_PERMISSIONS['adduser']['EVERYONE'] = false;
$_PERMISSIONS['adduser']['MEMBER'] = false;
$_PERMISSIONS['adduser']['ADMIN'] = true;
$_PERMISSIONS['adduser']['USERMANAGER'] = false;
$_PERMISSIONS['adduser']['BROWSERMANAGER'] = false;
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
$_PERMISSIONS['addbrowser']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['addbrowser']['CHECKINBROWSER'] = false;
$_PERMISSIONS['addbrowser']['CASHREGISTERBROWSER'] = false;

//Permissions for the edit browser page
$_PERMISSIONS['editbrowser']['EVERYONE'] = false;
$_PERMISSIONS['editbrowser']['MEMBER'] = false;
$_PERMISSIONS['editbrowser']['ADMIN'] = true;
$_PERMISSIONS['editbrowser']['USERMANAGER'] = false;
$_PERMISSIONS['editbrowser']['BROWSERMANAGER'] = true;
$_PERMISSIONS['editbrowser']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['editbrowser']['CHECKINBROWSER'] = false;

//Permissions for the change password page
$_PERMISSIONS['changepassword']['EVERYONE'] = false;
$_PERMISSIONS['changepassword']['MEMBER'] = true;
$_PERMISSIONS['changepassword']['ADMIN'] = true;
$_PERMISSIONS['changepassword']['USERMANAGER'] = true;
$_PERMISSIONS['changepassword']['BROWSERMANAGER'] = true;
$_PERMISSIONS['changepassword']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['changepassword']['CHECKINBROWSER'] = false;
$_PERMISSIONS['changepassword']['CASHREGISTERBROWSER'] = false;

//Permissions for the check in page
$_PERMISSIONS['check-in']['EVERYONE'] = false;
$_PERMISSIONS['check-in']['MEMBER'] = false;
$_PERMISSIONS['check-in']['ADMIN'] = true;
$_PERMISSIONS['check-in']['USERMANAGER'] = false;
$_PERMISSIONS['check-in']['BROWSERMANAGER'] = false;
$_PERMISSIONS['check-in']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['check-in']['CHECKINBROWSER'] = true;

//Permissions for the logout page
$_PERMISSIONS['logout']['EVERYONE'] = true;
$_PERMISSIONS['logout']['MEMBER'] = true;
$_PERMISSIONS['logout']['ADMIN'] = true;
$_PERMISSIONS['logout']['USERMANAGER'] = true;
$_PERMISSIONS['logout']['BROWSERMANAGER'] = true;
$_PERMISSIONS['logout']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['logout']['CHECKINBROWSER'] = true;
$_PERMISSIONS['logout']['CASHREGISTERBROWSER'] = false;

//Permissions for the profile page
$_PERMISSIONS['profile']['EVERYONE'] = false;
$_PERMISSIONS['profile']['MEMBER'] = true;
$_PERMISSIONS['profile']['ADMIN'] = true;
$_PERMISSIONS['profile']['USERMANAGER'] = true;
$_PERMISSIONS['profile']['BROWSERMANAGER'] = true;
$_PERMISSIONS['profile']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['profile']['CHECKINBROWSER'] = false;
$_PERMISSIONS['profile']['CASHREGISTERBROWSER'] = false;

//Permissions for the reset password page
$_PERMISSIONS['resetpassword']['EVERYONE'] = true;
$_PERMISSIONS['resetpassword']['MEMBER'] = true;
$_PERMISSIONS['resetpassword']['ADMIN'] = true;
$_PERMISSIONS['resetpassword']['USERMANAGER'] = true;
$_PERMISSIONS['resetpassword']['BROWSERMANAGER'] = true;
$_PERMISSIONS['resetpassword']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['resetpassword']['CHECKINBROWSER'] = true;
$_PERMISSIONS['resetpassword']['CASHREGISTERBROWSER'] = false;

//Permissions for the user manager page
$_PERMISSIONS['manageuser']['EVERYONE'] = false;
$_PERMISSIONS['manageuser']['MEMBER'] = false;
$_PERMISSIONS['manageuser']['ADMIN'] = true;
$_PERMISSIONS['manageuser']['USERMANAGER'] = true;
$_PERMISSIONS['manageuser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['manageuser']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['manageuser']['CHECKINBROWSER'] = false;
$_PERMISSIONS['manageuser']['CASHREGISTERBROWSER'] = false;

//Permissions for the edit user page
$_PERMISSIONS['edituser']['EVERYONE'] = false;
$_PERMISSIONS['edituser']['MEMBER'] = false;
$_PERMISSIONS['edituser']['ADMIN'] = true;
$_PERMISSIONS['edituser']['USERMANAGER'] = true;
$_PERMISSIONS['edituser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['edituser']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['edituser']['CHECKINBROWSER'] = false;
$_PERMISSIONS['edituser']['CASHREGISTERBROWSER'] = false;

//Permissions for the weekly winner page
$_PERMISSIONS['weeklywinner']['EVERYONE'] = false;
$_PERMISSIONS['weeklywinner']['MEMBER'] = false;
$_PERMISSIONS['weeklywinner']['ADMIN'] = true;
$_PERMISSIONS['weeklywinner']['USERMANAGER'] = true;
$_PERMISSIONS['weeklywinner']['BROWSERMANAGER'] = false;
$_PERMISSIONS['weeklywinner']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['weeklywinner']['CHECKINBROWSER'] = false;
$_PERMISSIONS['weeklywinner']['CASHREGISTERBROWSER'] = false;

//Permissions for the check-in page
$_PERMISSIONS['checkin']['EVERYONE'] = false;
$_PERMISSIONS['checkin']['MEMBER'] = false;
$_PERMISSIONS['checkin']['ADMIN'] = true;
$_PERMISSIONS['checkin']['USERMANAGER'] = false;
$_PERMISSIONS['checkin']['BROWSERMANAGER'] = false;
$_PERMISSIONS['checkin']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['checkin']['CHECKINBROWSER'] = true;
$_PERMISSIONS['checkin']['CASHREGISTERBROWSER'] = false;

//Permissions for the email management page
$_PERMISSIONS['manageemail']['EVERYONE'] = false;
$_PERMISSIONS['manageemail']['MEMBER'] = false;
$_PERMISSIONS['manageemail']['ADMIN'] = true;
$_PERMISSIONS['manageemail']['USERMANAGER'] = true;
$_PERMISSIONS['manageemail']['BROWSERMANAGER'] = false;
$_PERMISSIONS['manageemail']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['manageemail']['CHECKINBROWSER'] = false;
$_PERMISSIONS['manageemail']['CASHREGISTERBROWSER'] = false;

//Permissions for the send email to users page
$_PERMISSIONS['sendemailtousers']['EVERYONE'] = false;
$_PERMISSIONS['sendemailtousers']['MEMBER'] = false;
$_PERMISSIONS['sendemailtousers']['ADMIN'] = true;
$_PERMISSIONS['sendemailtousers']['USERMANAGER'] = true;
$_PERMISSIONS['sendemailtousers']['BROWSERMANAGER'] = false;
$_PERMISSIONS['sendemailtousers']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['sendemailtousers']['CHECKINBROWSER'] = false;
$_PERMISSIONS['sendemailtousers']['CASHREGISTERBROWSER'] = false;

//Permissions for the edit email page
$_PERMISSIONS['editemail']['EVERYONE'] = false;
$_PERMISSIONS['editemail']['MEMBER'] = false;
$_PERMISSIONS['editemail']['ADMIN'] = true;
$_PERMISSIONS['editemail']['USERMANAGER'] = true;
$_PERMISSIONS['editemail']['BROWSERMANAGER'] = false;
$_PERMISSIONS['editemail']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['editemail']['CHECKINBROWSER'] = false;
$_PERMISSIONS['editemail']['CASHREGISTERBROWSER'] = false;

//Permissions for the reset password page
$_PERMISSIONS['resetpassword']['EVERYONE'] = true;
$_PERMISSIONS['resetpassword']['MEMBER'] = true;
$_PERMISSIONS['resetpassword']['ADMIN'] = true;
$_PERMISSIONS['resetpassword']['USERMANAGER'] = true;
$_PERMISSIONS['resetpassword']['BROWSERMANAGER'] = true;
$_PERMISSIONS['resetpassword']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['resetpassword']['CHECKINBROWSER'] = true;
$_PERMISSIONS['resetpassword']['CASHREGISTERBROWSER'] = false;

//Permissions for the cash register page
$_PERMISSIONS['cashregister']['EVERYONE'] = false;
$_PERMISSIONS['cashregister']['MEMBER'] = false;
$_PERMISSIONS['cashregister']['ADMIN'] = false;
$_PERMISSIONS['cashregister']['USERMANAGER'] = false;
$_PERMISSIONS['cashregister']['BROWSERMANAGER'] = false;
$_PERMISSIONS['cashregister']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['cashregister']['CHECKINBROWSER'] = false;
$_PERMISSIONS['cashregister']['CASHREGISTERBROWSER'] = true;

//Permissions for the page not found page
$_PERMISSIONS['pagenotfound']['EVERYONE'] = true;
$_PERMISSIONS['pagenotfound']['MEMBER'] = true;
$_PERMISSIONS['pagenotfound']['ADMIN'] = true;
$_PERMISSIONS['pagenotfound']['USERMANAGER'] = true;
$_PERMISSIONS['pagenotfound']['BROWSERMANAGER'] = true;
$_PERMISSIONS['pagenotfound']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['pagenotfound']['CHECKINBROWSER'] = true;
$_PERMISSIONS['pagenotfound']['CASHREGISTERBROWSER'] = false;
