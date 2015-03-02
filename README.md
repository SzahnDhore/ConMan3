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


## ToDo-list

* Add data validation for the user data form.
* Make it possible to delete events.
* Make it possible to delete schedule posts.
* Make it possible for users to sign up for events.
* Have the system auto-generate the menu.
* Put all page content in the database.
* Rearrange the code to better reflect how I want the design pattern to work.


## Changelog

### Current version

#### v0.4 - 2015-02-26

* Users can now change their user data.
* A throttle system is in place to keep brute force crackers at bay.


### Earlier versions

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
