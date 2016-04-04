# Stippers
## What is it?
Stippers is simple website that clubs can use to keep track of their members.
It also offers some extra features such as the ability for members to login to the website in order to access member-only content and to register when members visit your club/headquarter by means of check-ins.
While it's primarily developed to meet the requirements for member registration and management of our own youth club (JH De Stip) it can freely be used by anyone.

## Is Stippers what I need?
If Stippers features everything you need and you have everything in place that's required for Stippers to perform it's basic functionality then you should give it a try.

There's one thing we ask you to do if you're using it, and that is to let us know by sending an email to [stan@stip.be].
This way we can keep track of how who is using it.

## Features
We've done our best to list everything Stippers has to offer you. Don't be mad if we forgot something though!

### Login
All users can login to the website as long as they are a member. When a user is not a member of the current membership period the account still exists but he/she will be unable to login. The only exception to this is the admin account, with which you'll always be able to login. The admin account is the initial account that's created during database setup and has user id 0. More on membership periods below!

### Membership periods, user registration and user management
People that are in the system (users) can be a member of your club. Memberships are always on a per membership period basis.
This means that when a user is registered as a member during a period, he will be a member until that period ends. Right now member periods are hard coded to be calendar years.

You can register new users to the system. A user must enter his/her data and also set a password. He basically creates an account that he can later on use to login to the website. When a user is created he is automatically registered as a member for the membership period we are currently in. With the current implementation he will be a member until 01/01 00:00..

This and the definition of a membership implies that there are no members anymore when a period ends. This is because a new period has started and nobody is registered as a member for that period yet.

You can renew users. This means that an existing user that is not a member of the current membership period (but he was for some period in the past) is registered as a member of the current period. Because the user already exists in the system he doesn't need to enter all his data again. He only needs to review if everything is still up to date and his account history will be left intact and available.

Anyone with manage users permissions or higher can manage users. This means they can search through all users on the system and also change most of their data (like their address, name, email address, ...). The results of a search are displayed in a table and can be exported to a .csv file.

### Check-in
Every user has a card number tied to his account for each period he was/is a member. This number can be used to perform check-ins. Every time your members come to your venue/club house/headquarter/whatever they can check-in. The exact time of all check-ins is recorded to the database. This gives you the ability to precicely keep track of what members visit you club and when they do.

Although there is currently not much information about the check-ins available through the GUI, all info is in the database. This enables you to really nice things. Think of things like giving a member a free beer when it's the fifth time he visits you this mongth. You don't have to manually keep track of this, just write your own some code that hooks into the checkin system and the system will notify you when this happens.

The admin can not check-in because he is never a member.

Note that users are not automatically checked in when they are created or renewed, this must be done explicitly.

### Chat
There is a chat room where members can talk to each other. This is one shared room for all members, sending private messages is currently not possible.

### Meat wheel
The meat wheel lets you spin a wheel to randomly select what you're going to order. This is more of a fun feature for us, but you can use or customize it if you want.

### Weekly winner
Every week you can randomly generate a winner of the week. When this user checks in a notification will be shown on the check-in page and all users with the userManager permission set (more on that later) will get a notification email. This member wins a price.

### Balance and cash register
Users can have money on their account. Users with the moneyManager permission set (more on that later) can increase or decrease the balance of any account. Browsers that are set as cash register can be used to take money off accounts (usually a computer behind the bar). This enables members to put money on their account and pay with their card.

A configurable discount can be given when a member pays with his/her card, though this is not required.

When money is added to an account it's possible to incidate whether it's real money or prize money. Prize money is for example a bonus that a member gets when he checks in as weekly winner, when it's his birthday or when his account is created.

Every wheek a report can be send to configured email addresses that lists the total amount of money on all cards, what's real money and what's prize money. This is useful to include in the bookkeeping.

Users can view all transactions for their account, and users with the userManager permission set can also view that of all other users. If there is any discussion about what happened to someone's money, this can be used to keep track of what happened when and where or by who.

### User profile
Members that are logged in can view their profile and some statistics regarding his/her check-ins. Members can also update their data and change their password.

### Permissions
For all pages on the website you can set permissions defining who can access it. When no permissions are set for a page this page will be unavailable for everyone, including the admin. When a specific permission is set for a page, all users or browsers with that permission will be able to access that page (except when their membership expired of course).

#### User permissions
Currently the following user permissions are available:
* member: by default all users have this permission.
* admin: admins can do anything. By default only the admin account has this permission set. Members with the admin permissions can set permissions of other users. Note here that there is a difference between the admin users and users with admin permissions. While multiple users can have admin permissions, there can be only one admin account.
* userManager: This permission allows a user to manage other users.
* browserManager: This permission allows the user to manage browsers and their permissions.
* moneyManager: This permission allows the user to change the balance on the accounts of other users.

#### Browser tied permissions
Some pages should not be visible from anywhere, but only from selected browsers. Think of the check-in page. Nobody should be able to check-in at home. The pages to add or renew users are another example, because users should get their membership card and pay you. This can only happen in your club.

This is where browser tied permissions come in to play. They allow you to give permissions to a specific browser. All users with the browserManager permission set can change permissions of browser.

Currently the following browser permissions are available:
* checkInBrowser: enables the browser to access the check-in page.
* addRenewUserBrowser: enables the browser to access the pages to add or renew users.
* cashRegisterBrowser: enables the browser to access the page to reduce the balance on the account of a member who's card is scanned.

When giving a permission to a browses the first time, the website must be visited from that browser to set the cookie. Later on the permissions for that browser can be changed from anywhere.
Because permissions are cookie based a browser will lose it's permissions when cookies are removed.
A browsers permissions can be revoked from any location, but the cookie will always be installed in the browser. The system will simply reject it.

### Runables
The IRunnable interface can be implemented by classes. Runnables do a certain job and are used to do actions without a user initiating it through interaction with the website. Cronjobs for example can run a runnable to send a weekly report of the money on the user accounts or to generate a weekly winner.

### API
There is an API to allow check-ins through REST calls. Currently this is the only functionality available through the API. It's used to allow our other project (Stippers Check In) to work. API calls will only work when passing a valid API key with the "key" get paramter in the url.

### Barcode scanner
Most of the pages where a card number can be entered have JavaScript in place to allow this field to be populated by scanning the card with a scanner.

## Requirements
Because Stippers is written in plain PHP with a little bit of JavaScript you don't need any additional frameworks. The only requirements are PHP 5.6 or higher and a MySQL database.

## Installation instructions
* Create a new database and run the SQL.sql script to create the necessary tables and procedures.
* Copy all other files (except for .htaccess) to your webserver and place them in the directory where you want Stippers be available at. LICENSE, README.md, SQL.sql, cronJobRunner.php and .gitignore don't need to be copied.
* Set all the configuration to your needs. It's recommended to open all files in the config directory and check if it's set to your likings. Are settings are documented in the comments.
* Browse to yourclub.be/stipperspath/createAdmin.php to create the admin account. You can first open that file to modify the admin user's data. This file should be removed after the admin user is successfuly created.
* Now copy over .htaccess to your websever of use your webservers configuration to redirect everything to index.php.
* You should now be able to login with the admin account.
* From the admin account set up a browser that can be used to add other users, act as cash register etc.
* When new users are added you can give additional permissions to other staff members of your club.
* It's recommended to make frequent database backups, especially if you're using the feature for members to have money on their account. You don't want your club's or members' money to disappear, do you?
* Happy Stipping!

## Sidenotes
### Language
Currently Stippers is only available in Dutch, but MUI support is on our bucket list (somewhere at the bottom, behind other functional features we need ourselves).

### Customization
The CSS that comes with Stippers is the default CSS we use for our own website. If you want yours to look differently you must edit the CSS.
If you want to change any behaviour or add functionality that's not currently there and not configurable in the config files you must write your own code.

## Requests
If you have any feature requests or proposals for improvements you're free to hit tell us. However remember that this is a one or two man project and new things will only be implemented when we have time. Features that we need for our own club will always get priority.