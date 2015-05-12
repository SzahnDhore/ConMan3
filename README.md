# ConMan3

**The Convention Manager Evolved**

-------------------------------------------------

A tool written in PHP to help organizing a convention. Primarily written for a gaming convention in Sweden, but should be just as usable for any kind of similar event.

The basic idea is to build a solid system for managing visitors and events at an RPG convention.

**Please note that this is yet work in progress. Many features will have to be written before it's finished and usable.**


## Features

* Twig templating engine to make the authoring of pages simpler.
* Secure login system.
    - Native login uses salted Blowfish hashing for passwords.
    - Login through social media account possible.
* Secure handling of MySQL data through prepared statements.


## Changelog

### Latest version

#### v0.6 - 2015-05-12
##### Bugfixes
* Changing personal details now works.
* Changing gender now works.
* Social login no longer fails.

##### New features
* New startpage for when the user has logged in.
* Misc texts has been updated.
* Added sleeping room to convention registration.
* The footer has been updated.
* Confirm payments has been implemented and the admin can change the registration if he/she needs to.

### Earlier versions

#### v0.5 - 2015-04-23
##### Bugfixes
* Entering the wrong password does not always notify the user that it is wrong.
* The page "arrangemang" crashes when no events exists.
* The page "Skapa nytt arrange­mang" crashes when no event types exists.
* My profile does not show the correct info.

##### New features

* The registration prices are stored in the database.
* Added support to send emails.
* Group based user system.
* Changed to a role based permission system.
* It is possible to sign up for the convention.
* Added data validation for the page my profile.

#### v0.4 - 2015-02-26

* Users can now change their user data.
* A throttle system is in place to keep brute force crackers at bay.

#### v0.3 - 2015-02-06

* Added project to Bitbucket.
* System now has basic functionality for adding events.


#### v0.2 - 2015-01-05

* Login system is almost in place.


#### v0.1 - 2015-01-04

* Created a changelog and a version chain. Should have done that a lot earlier...
* Uses Twig for templating. I had a native templating engine before, but it turned out a bit too simple for my needs.
* Some attempt at using a design pattern has been made. It has not been implemented very consistently though.
* All static page content is placed in files rather than in the database.
