# AutoMngr

Manage Cars, Contacts and Contracts

- Export as Excel
- Print Contracts as PDF

Running on Laravel 8

## Project Setup

Prerequisites: 
- [Docker](https://docker.com) installed and running
- Docker compose installed
- Composer installed
- PHP installed

Steps:
1. copy .env.example to .env
2. add user credentials in .env
2. run `composer update --ignore-platform-reqs`
3. run `composer install --ignore-platform-reqs`
1. run `./vendor/bin/sail php artisan key:generate`
2. start app by running `./vendor/bin/sail up` inside the main directory (or configure a bash alias: `alias sail='bash vendor/bin/sail'`) then you can use `sail up`
3. Run migrations and seed db: `sail php artisan migrate:fresh --seed`
4. `sail npm run watch`
5. Access the web application at `0.0.0.0`
6. The default credentials can be seen/set in the .env