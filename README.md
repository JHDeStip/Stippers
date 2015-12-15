# Stippers
## What is it?
Stippers is simple website that organizations can use to keep track of their members.
It also offers some extra features such as the ability for members to login to the website in order to access member-only content and to register when members visit your club/headquarter by means of check-ins.
While it's primarily developed to meet the requirements for member registration and management of our own youth club (JH De Stip) it can freely be used by anyone.

## Is Stippers what I need?
If Stippers features everything you need and you have everything in place that's required for stippers to perform it's basic functionality then you should give it a try.

There's one thing we ask you to do if you're using it, and that is to let us know by sending an email to [stan@stip.be].
This way we can keep track of how many organisations are using it.

## Features
We've done our best to list everything Stippers has to offer you. Don't be mad if we forgot something though!

### Login
All users can login to the website as long as they are a member. When a user is not a member of the current membership period his account still exists but he will be unable to login. The only exception to this is the admin account, with which you'll always be able to login. The admin account is the initial account that's created during database setup and has user id 0. More on membership periods below!

### Membership periods, user registration and user management
People that are in the system (users) can be a member of your organisation. Memberships are always on a per membership period basis.
This means that when a user is register as a member during a period, he will be a member until that period ends. Right now member periods are hard coded to be calendar years.

You can register new users to the system. A user must enter his data and also set a password. He basically creates an account that he can lateron use to login to the website. When a user is created he is automatically registered as a member for the membership period we are currently in. With the current implementation he will be a member until 01/01 00:00..

This and the definition of a membership implies that there are no members anymore when a period ends. This is because a new period has started and nobody is registered as a member for that period yet.

You can renew users. This means that an existing user that is not a member of the current membership period (but he was for some period in the past) is registered as a member of the current period. Because the user already exists in the system he doesn't need to enter all his data again. He only needs to review if everything is still up to date, and his account history will be left intact and available.

Anyone with manage users permissions or higher can manage users. This means they can search through all users on the system and also change most of their data (like their address, name, email address, ...).

### Check-in
Every user has a card number tied to it for each period he was/is a member. This number can be used to perform check-ins. Every time your members come to your venue/club house/headquarter/whatever they can check-in. The exact time of all check-ins is recorded to the database. This gives you the ability to precicely keep track of what members visit you club and when they do.

Although there is currently not much information about the check-ins available through the GUI, all info is in the database. This enables you to really nice things. Think of things like giving a member a free beer if it's the fifth time he visits you this mongth. You don't have to manually keep track of this, just write your own some code that hooks into the checkin system and the system will notify you when this happens.

The only exception is the admin account

### User profile
When a member logs in he can view his profile and some statistics regarding his check-ins. Here can also update his data and change his password

### Permissions
For all pages on the website you can set permissions defining who can access it. When no permissions are set for a page this page will be unavailable for everyone, including the admin. When a specific permission is set for a page, all users or computers with that permission will be able to access that page (except when their membership expired of course).

#### User permissions
Currently the following user permissions are available:
* member: by default all users have this permission.
* admin: admins can do anything. By default only the admin account has this permission set. Members with the admin permissions can set permissions of other users. Not here that there is a difference between the admin users and users with admin permissions. While multiple users can have admin permissions, there can be only one admin account.
* userManager: This permission allows a user to manage other users.
* authorizedBrowserManager: This permission allows the user to manage browser tied permissions.

#### Browser tied permissions
Some pages should not be visible from anywhere, but only from selected browsers. Think of the check-in page. Nobody should be able to check-in at home. The pages to add or renew users are another example, because users should get their membership card and pay you. This can only happen in your club.

This is where browser tied permissions come in to play. They allow you to give permissions to a specific browser. All users with the authorizedBrowserManager permission set can change permissions of browser.

Currently the following browser permissions are available:
* checkInBrowser: enables the browser to access the check-in page.
* addRenewUserBrowser: enables the browser to access the pages to add or renew users.

When giving a permissions to a browse the first time, the website must be visited from that browser to set the cookie. Lateron the permissions for that browser can be changed from anywhere.
Because permissions are cookie based a browser will lose it's permissions when cookies are removed.
A browsers permissions can be revoked from any location, but the cookie will always be installed in the browser. The system will simply reject it.

## Requirements
Because Stippers is written in plain PHP with a little bit of JavaScript you don't need any additional frameworks. The only requirements are PHP 5.6 or higher and a MySQL database.

## Sidenotes
### Language
Currently Stippers is only available with in Dutch, but MUI support is on our bucket list (somewhere at the bottom, behind other functional features we need ourselves).

### Customization
The CSS that comes with Stippers is the default CSS we use for our own website. If you want yours to look differently you must edit the CSS.
If you want to change any behaviour or add functionality that's not currently there and not configurable in the config files you must write your own code.

## Requests
If you have any feature requests or proposals for improvements you're free to hit tell us. However remember that this is a one or two man project and new things will only be implemented when we have time. Features that we need for our own club will always get priority.
