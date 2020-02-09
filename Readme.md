# Lightning PHP MVC Framework

**Goal**

Lightning aims to be a blazing fast and lightweight PHP MVC framework, implementing all of the most important features of a PHP framework without the fluff. Includes Vue and Twig for front-end development.

**Requirements**

* PHP 7.2+

**Installing**

* Download or clone the repo
* Open a command line, `cd` to the project directory, and run `composer install`
* Rename `.env.example` to `.env` and input your server configuration and preferences
* Run `npm install` and `composer install`

All settings are found in `.env` and should be relatively straightforward.

**Features Overview**

* Flexible routing system which handles clean URLs and implements a `/controller/action/[optional prarameter]` scheme. This can be easily configured to suit most needs.
* User account system with registration, login, "Remember Me", and "Forgot Password" functionality.
* Flash message alert system for displaying messages to users.
* Mailing system with both text/plain and HTML email support.
* Secure token generation and verification system.
* Error handler and logging system which can display better error messages than PHP on development servers, and serves custom error pages on production servers (while saving error logs).
* Robust utilities class offering methods such as SSL detection, AJAX request detection, various date/time/timezone conversions, a function to convert *any* string to a proper URL slug, and more.
* Templating system via Twig and a `View` class to help with rendering, passing in custom data, and template caching.
* HTML Purifier is included to allow only safe HTML output and prevent XSS attacks. Use `Utilities::purifyOutput`.
* Vue and Vue CLI for front-end coding and HMR.
* SCSS for styles.

**Development Environment**

1. Make sure `ENVIRONMENT` in `.env` is set to `dev`.
2. Open a command line and run `vue ui`. You will need to have Vue CLI installed (`npm install -g @vue/cli` if you don't have it).
3. Import the Lightning folder in Vue UI.
4. Go to `Tasks->dev` and click the "Run task" button.
5. Navigate to your local URL for the project (for example http://lightning.local/ or whatever you set up for it on your local server); assets will automatically be served from http://localhost:8080/ (so you don't need to go to this address) when in `dev` mode in `.env`, and the real URL when in `prod`.


**Reference Docs**

***Framework Methods and Usage coming soon!***
