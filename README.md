# AutoMngr

Manage Cars, Contacts and Contracts

- Export as Excel
- Print Contracts as PDF

Running on Laravel 8

## Project Setup

Prequisites: 
- [Docker](https://docker.com) installed and running

1. copy .env.example to .env
2. start app by running `./vendor/bin/sail up` inside the main directory (or configure a bash alias: `alias sail='bash vendor/bin/sail'`) then you can use `sail up`
3. Run migrations and seed db: `sail php artisan migrate:fresh --seed`
4. `sail npm run watch`
5. Access the web application at `0.0.0.0`
6. The default credentials are `hello@salloum.ch` and `abc123`
