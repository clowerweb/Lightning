# Lightning PHP MVC Framework

**Goal**

Lightning aims to be a blazing fast and lightweight PHP MVC framework, implementing all of the most important features of a PHP framework without the fluff. The included example page scores a 100 for both mobile and desktop on Google PageSpeed Insights.

**Requirements**

* PHP 7.1+
* Composer (for installation of third party libraries)

**Installing**

* `cd` to the project directory and run `composer install`

**Features Overview**

* Flexible routing system which handles clean URLs and implements a `/controller/action/[optional prarameter]` scheme. This can be easily configured to suit most needs.
* Flash message alert system for displaying messages to users.
* Mailing system (using PHPMailer).
* Secure token generation and verification system.
* Error handler and logging system which can display better error messages than PHP on development servers, and serves custom error pages on production servers (while saving error logs).
* Robust utilities class offering methods such as SSL detection, AJAX request detection, various date/time/timezone conversions, a function to convert *any* string to a proper URL slug, and more.
* Templating system via Twig and a `View` class to help with rendering, passing in custom data, and template caching.
* Allow your users to use an HTML WYSIWYG editor with confidence, as HTML Purifier is included to allow only safe HTML output and prevent XSS attacks. The example template has a demonstration of this in action.

**Reference Docs**

Coming soon!