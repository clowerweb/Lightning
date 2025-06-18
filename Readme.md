# PHP Lightning

Lightning aims to be a blazing fast and lightweight headless PHP Controller-Model-Service framework, implementing all of the most important features of a PHP framework without the fluff. Includes Vue/NUXT 3 and Tailwind for front-end development, and a headless GraphQL-based back-end.

## Website

The website for Lightning is https://phplightning.com/ -- currently, this is exactly the project you get here and what you should see when you get the project running locally after installation. On the roadmap, it'll serve as the documentation website, but consider it a demo for now.

## How Fast is Lightning, Really?

Lightning currently scores all 100s in Lighthouse across the board for Performance, Accessibility, Best Practices, and SEO! Leveraging Nuxt with SSR and GraphQL, along with a sleek but deceptively powerful and optimized PHP 8 back-end, Lightning truly lives up to its name.

## Requirements

* Docker for local dev
* PHP 8.2+ (installed with Docker for local)
* Composer
* Node 22+
* MySQL or MariaDB (installed with Docker for local)
* Apache or nginx (nginx installed with Docker for local; .htaccess provided for Apache compatibility)

## Templates
Lightning has a basic templating system. Source template files are located in `/templates`, and there's a sample Nuxt TypeScript + Tailwind template that utilizes the GraphQL API under `/templates/default`. You can modify this template for your project, or create your own template in the `/templates` directory and set `SITE_TEMPLATE` in your .env file.

## Local Dev

1. Copy `.env.example` to `.env` (you don't need to make any changes for local; the installer will handle them)
2. `composer install`
3. `npm install`
4. `docker-compose up -d`
5. App will be available at `http://lightning.local` -- No need to run `npm run dev` -- Docker will run this automatically, and should have full HMR support
6. The installer should run. For local, you won't need to change anything other than the Admin Password
    * You can optionally change the Database Name and Admin Username as well if you want to. Everything else should stay the same!
    * **DO NOT** change the Database Host, Database Username, or Database Password for local! These _must_ be `db`, `root`, and `password` respectively for the local Docker setup!
7. You can now access the admin at `http://lightning.local/admin`

### Changing the local URL
To change the default local URL, simply change `lightning.local.conf` in `/docker/nginx/sites/` to `whatever.local.conf` and replace all instances of `lightning.local` with `whatever.local`

* If you're using Cypress, also change the `baseUrl: 'https://lightning.local'` to `baseUrl: 'https://whatever.local'` in `/templates/default/cypress.config.ts`
* You may also need to update `/docker/nginx/ssl/generate-cert.ps1`, `/docker/nginx/ssl/generate.bat`, and `/docker/nginx/ssl/openssl.conf`
* Be sure to run `docker-compose down` and `docker-compose up -d --build` if changing the site URL

### Cypress
The default template comes with some basic Cypress tests to get you started.

1. In `/templates/default/cypress.config.ts` (or whichever template you are using), set `baseUrl: 'https://lightning.local'` to the local URL you are using for your project
2. Sample tests can be found in `/templates/default/e2e/main.cy.ts`
3. Simply run `npm run test` to run the tests. The tests will run from the template configured in the .env file for `SITE_TEMPLATE`

## Building for Production
1. From the project root, run `npm run build`. This will run the build for the admin templates as well as the template in the .env file for `SITE_TEMPLATE`

## Running on Production

1. Copy `/App`, `/Lightning`, `/public`, `/.env.example`, and `/composer.json` to your server
2. Rename `.env.example` to `.env` (**don't** copy your local `.env` file to production!) 
3. Run `composer install`
2. Run the installer for prod and create the db and admin user (just go to the main address, or go to `yourdomain.tld/install.php`)
4. If using Apache, just point your site root to `/public` and you're done!
5. If using nginx, use `/_dev/nginx.conf` as a template and modify as noted in the comments at the top of the file
