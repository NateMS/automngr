# AutoMngr

Manage Cars, Contacts and Contracts

- Export as Excel
- Print Contracts as PDF

Running on Laravel 9

## Project Setup

Prerequisites: 
- [Docker](https://docker.com) installed and running

Steps:
1. copy .env.example to .env
2.
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
4. start app by running `./vendor/bin/sail up` inside the main directory (or configure a bash alias: `alias sail='bash vendor/bin/sail'`) then you can use `sail up`
5. run `./vendor/bin/sail php artisan key:generate`
6. Run migrations and seed db: `sail php artisan migrate:fresh --seed`
7. `sail npm install`
8. `sail npm run watch`
9. Access the web application at `0.0.0.0`

If you get permission error when trying to generate the key:
```
sail root-shell
chmod -R 777 /var/www/html/
chown -R www-data:www-data /var/www/html/
exit
sail down
sail up -d
```
