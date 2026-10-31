# Agency CMS

A small Laravel CMS for a one-page agency website. The public site is a Bootstrap 5 one-pager (services, portfolio, timeline, team, contact form). The admin area is a Bootstrap 4 dashboard for managing the content, the site options and the contact messages.

Built with Laravel 10, Livewire 3 + Volt and Vite.

## Requirements

- PHP 8.1 with `pdo_mysql` and `pdo_sqlite`
- MySQL 8
- Node.js and npm
- Composer (a `composer.phar` is included)

## Setup

```bash
cp .env.example .env
# set DB_DATABASE, DB_USERNAME, DB_PASSWORD (and optionally the SB_/SEED_ values) in .env

php composer.phar install
php artisan key:generate
php artisan migrate --seed

npm install
npm run build      # or: npm run dev
```

The seeder creates one admin user from `SEED_ADMIN_EMAIL` / `SEED_ADMIN_PASSWORD` in `.env` (default `admin@example.com` / `password`). The admin area is at `/admin`.

Contact form messages are emailed to `SB_ADMIN_EMAIL` until it's changed in Admin › Options.

## Tests

```bash
php artisan test
```

Tests run on an in-memory SQLite database (see `phpunit.xml`), so they never touch the MySQL data.

## Credits

The admin and public themes are based on [SB Admin 2](https://startbootstrap.com/theme/sb-admin-2) and [Agency](https://startbootstrap.com/theme/agency) by Start Bootstrap, licensed under MIT.
