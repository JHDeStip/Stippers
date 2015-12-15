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
All users can login to the website as long as they are a member. When a user is not a member of the current membership period his account still exists but he will be unable to login. The only exception to this is the admin account, with which you'll always be able to login. More on membership periods below!

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

### User profile
When a member logs in he can view his profile and some statistics regarding his check-ins. Here can also update his data and change his password.

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
