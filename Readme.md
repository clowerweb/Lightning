# Lightning PHP MVC Framework

**Goal**

Lightning aims to be a blazing fast and lightweight PHP MVC framework, implementing all of the most important features of a PHP framework without the fluff. The included example page scores a 100 for both mobile and desktop on Google PageSpeed Insights.

**Requirements**

* PHP 7.2+
* Composer

**Installing**

* Download or clone the repo
* Open a command line, `cd` to the project directory, and run `composer install`
* Rename `/App/Config.sample` to `Config.php` and input your server configuration and preferences
* Run `npm install` if you'd like to use Grunt and Browsersync

All settings are found in `Config.php` and should be relatively straightforward.

**Features Overview**

* Flexible routing system which handles clean URLs and implements a `/controller/action/[optional prarameter]` scheme. This can be easily configured to suit most needs.
* User account system with registration, login, "Remember Me", and "Forgot Password" functionality.
* Flash message alert system for displaying messages to users.
* Mailing system with both text/plain and HTML email support.
* Secure token generation and verification system.
* Error handler and logging system which can display better error messages than PHP on development servers, and serves custom error pages on production servers (while saving error logs).
* Robust utilities class offering methods such as SSL detection, AJAX request detection, various date/time/timezone conversions, a function to convert *any* string to a proper URL slug, and more.
* Templating system via Twig and a `View` class to help with rendering, passing in custom data, and template caching.
* Allow your users to use an HTML WYSIWYG editor with confidence, as HTML Purifier is included to allow only safe HTML output and prevent XSS attacks. The example template has a demonstration of this in action.

**Reference Docs**

***Framework Methods and Usage coming soon!***
