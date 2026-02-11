# AutoMngr

Manage Cars, Contacts and Contracts

- Export as Excel
- Print Contracts as PDF

Laravel 10 + Vue 3 (Inertia.js)

## Local Development

Prerequisites:
- [Docker](https://docker.com) installed and running

Steps:
1. Copy `.env.example` to `.env`
2. Install PHP dependencies:
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```
3. Start the app: `./vendor/bin/sail up -d`
4. Generate app key: `./vendor/bin/sail php artisan key:generate`
5. Run migrations and seed: `./vendor/bin/sail php artisan migrate:fresh --seed`
6. Install frontend dependencies: `./vendor/bin/sail npm install`
7. Watch for changes: `./vendor/bin/sail npm run watch`
8. Access the app at `http://localhost`

## Deployment

The app is deployed as a Docker container to a Hetzner VPS with Caddy as reverse proxy.

### How it works

1. Push to `master` triggers the GitHub Actions workflow at `.github/workflows/deploy.yml`
2. The workflow builds a Docker image and pushes it to GitHub Container Registry (`ghcr.io/natems/automngr`)
3. It then SSHes into the VPS and pulls the new image, restarts the container, and runs migrations

The deploy path on the VPS is hardcoded to `/opt/vps/services/automngr` in the workflow.

### Manual deploy

```bash
ssh your-vps
cd /opt/vps/services/automngr
docker compose pull
docker compose down && docker compose up -d
docker compose exec -T app php artisan migrate --force
```
