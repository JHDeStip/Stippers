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
$_PERMISSIONS['home']['EVERYONE'] = false;
$_PERMISSIONS['home']['MEMBER'] = true;
$_PERMISSIONS['home']['ADMIN'] = true;
$_PERMISSIONS['home']['USERMANAGER'] = true;
$_PERMISSIONS['home']['BROWSERMANAGER'] = true;
$_PERMISSIONS['home']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['home']['CHECKINBROWSER'] = false;

//Permissions for the add or renew user page
$_PERMISSIONS['addorrenewuser']['EVERYONE'] = false;
$_PERMISSIONS['addorrenewuser']['MEMBER'] = false;
$_PERMISSIONS['addorrenewuser']['ADMIN'] = true;
$_PERMISSIONS['addorrenewuser']['USERMANAGER'] = false;
$_PERMISSIONS['addorrenewuser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['addorrenewuser']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['addorrenewuser']['CHECKINBROWSER'] = false;

//Permissions for the add user page
$_PERMISSIONS['adduser']['EVERYONE'] = false;
$_PERMISSIONS['adduser']['MEMBER'] = false;
$_PERMISSIONS['adduser']['ADMIN'] = true;
$_PERMISSIONS['adduser']['USERMANAGER'] = false;
$_PERMISSIONS['adduser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['adduser']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['adduser']['CHECKINBROWSER'] = false;

//Permissions for the renew user page
$_PERMISSIONS['renewuser']['EVERYONE'] = false;
$_PERMISSIONS['renewuser']['MEMBER'] = false;
$_PERMISSIONS['renewuser']['ADMIN'] = true;
$_PERMISSIONS['renewuser']['USERMANAGER'] = false;
$_PERMISSIONS['renewuser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['renewuser']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['renewuser']['CHECKINBROWSER'] = false;

//Permissions for the renew user search page
$_PERMISSIONS['renewusersearch']['EVERYONE'] = false;
$_PERMISSIONS['renewusersearch']['MEMBER'] = false;
$_PERMISSIONS['renewusersearch']['ADMIN'] = true;
$_PERMISSIONS['renewusersearch']['USERMANAGER'] = false;
$_PERMISSIONS['renewusersearch']['BROWSERMANAGER'] = false;
$_PERMISSIONS['renewusersearch']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['renewusersearch']['CHECKINBROWSER'] = false;

//Permissions for the manage browser page
$_PERMISSIONS['managebrowser']['EVERYONE'] = false;
$_PERMISSIONS['managebrowser']['MEMBER'] = false;
$_PERMISSIONS['managebrowser']['ADMIN'] = true;
$_PERMISSIONS['managebrowser']['USERMANAGER'] = false;
$_PERMISSIONS['managebrowser']['BROWSERMANAGER'] = true;
$_PERMISSIONS['managebrowser']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['managebrowser']['CHECKINBROWSER'] = false;

//Permissions for the add browser page
$_PERMISSIONS['addbrowser']['EVERYONE'] = false;
$_PERMISSIONS['addbrowser']['MEMBER'] = false;
$_PERMISSIONS['addbrowser']['ADMIN'] = true;
$_PERMISSIONS['addbrowser']['USERMANAGER'] = false;
$_PERMISSIONS['addbrowser']['BROWSERMANAGER'] = true;
$_PERMISSIONS['addbrowser']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['addbrowser']['CHECKINBROWSER'] = false;

//Permissions for the browser manager page
$_PERMISSIONS['editbrowser']['EVERYONE'] = false;
$_PERMISSIONS['editbrowser']['MEMBER'] = false;
$_PERMISSIONS['editbrowser']['ADMIN'] = true;
$_PERMISSIONS['editbrowser']['USERMANAGER'] = false;
$_PERMISSIONS['editbrowser']['BROWSERMANAGER'] = true;
$_PERMISSIONS['editbrowser']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['editbrowser']['CHECKINBROWSER'] = false;

//Permissions for the change password page
$_PERMISSIONS['changepassword']['EVERYONE'] = false;
$_PERMISSIONS['changepassword']['MEMBER'] = true;
$_PERMISSIONS['changepassword']['ADMIN'] = true;
$_PERMISSIONS['changepassword']['USERMANAGER'] = true;
$_PERMISSIONS['changepassword']['BROWSERMANAGER'] = true;
$_PERMISSIONS['changepassword']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['changepassword']['CHECKINBROWSER'] = false;

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

//Permissions for the profile page
$_PERMISSIONS['profile']['EVERYONE'] = false;
$_PERMISSIONS['profile']['MEMBER'] = true;
$_PERMISSIONS['profile']['ADMIN'] = true;
$_PERMISSIONS['profile']['USERMANAGER'] = true;
$_PERMISSIONS['profile']['BROWSERMANAGER'] = true;
$_PERMISSIONS['profile']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['profile']['CHECKINBROWSER'] = false;

//Permissions for the reset password page
$_PERMISSIONS['resetpassword']['EVERYONE'] = true;
$_PERMISSIONS['resetpassword']['MEMBER'] = true;
$_PERMISSIONS['resetpassword']['ADMIN'] = true;
$_PERMISSIONS['resetpassword']['USERMANAGER'] = true;
$_PERMISSIONS['resetpassword']['BROWSERMANAGER'] = true;
$_PERMISSIONS['resetpassword']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['resetpassword']['CHECKINBROWSER'] = true;

//Permissions for the user manager page
$_PERMISSIONS['manageuser']['EVERYONE'] = false;
$_PERMISSIONS['manageuser']['MEMBER'] = false;
$_PERMISSIONS['manageuser']['ADMIN'] = true;
$_PERMISSIONS['manageuser']['USERMANAGER'] = true;
$_PERMISSIONS['manageuser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['manageuser']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['manageuser']['CHECKINBROWSER'] = false;

//Permissions for the edit user page
$_PERMISSIONS['edituser']['EVERYONE'] = false;
$_PERMISSIONS['edituser']['MEMBER'] = false;
$_PERMISSIONS['edituser']['ADMIN'] = true;
$_PERMISSIONS['edituser']['USERMANAGER'] = true;
$_PERMISSIONS['edituser']['BROWSERMANAGER'] = false;
$_PERMISSIONS['edituser']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['edituser']['CHECKINBROWSER'] = false;

//Permissions for the weekly winner page
$_PERMISSIONS['weeklywinner']['EVERYONE'] = false;
$_PERMISSIONS['weeklywinner']['MEMBER'] = false;
$_PERMISSIONS['weeklywinner']['ADMIN'] = true;
$_PERMISSIONS['weeklywinner']['USERMANAGER'] = true;
$_PERMISSIONS['weeklywinner']['BROWSERMANAGER'] = false;
$_PERMISSIONS['weeklywinner']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['weeklywinner']['CHECKINBROWSER'] = false;

//Permissions for the check-in page
$_PERMISSIONS['checkin']['EVERYONE'] = false;
$_PERMISSIONS['checkin']['MEMBER'] = false;
$_PERMISSIONS['checkin']['ADMIN'] = true;
$_PERMISSIONS['checkin']['USERMANAGER'] = false;
$_PERMISSIONS['checkin']['BROWSERMANAGER'] = false;
$_PERMISSIONS['checkin']['ADDRENEWUSERBROWSER'] = false;
$_PERMISSIONS['checkin']['CHECKINBROWSER'] = true;

//Permissions for the page not found page
$_PERMISSIONS['pagenotfound']['EVERYONE'] = true;
$_PERMISSIONS['pagenotfound']['MEMBER'] = true;
$_PERMISSIONS['pagenotfound']['ADMIN'] = true;
$_PERMISSIONS['pagenotfound']['USERMANAGER'] = true;
$_PERMISSIONS['pagenotfound']['BROWSERMANAGER'] = true;
$_PERMISSIONS['pagenotfound']['ADDRENEWUSERBROWSER'] = true;
$_PERMISSIONS['pagenotfound']['CHECKINBROWSER'] = true;