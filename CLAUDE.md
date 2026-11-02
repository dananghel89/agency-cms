# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

A Laravel 10 app (PHP ^8.1) that wraps two Start Bootstrap themes. One is a public "Agency" one-page site on Bootstrap 5. The other is an "SB Admin 2" CMS on Bootstrap 4 that manages the site's content. Interactive UI uses Livewire 3 with Volt single-file components. Auth comes from Laravel Breeze (Volt stack) and was then trimmed down. Project-wide settings live in `config/project.php` (`config('project.…')`).

## Commands

Local environment: WAMP on Windows. This project runs on PHP 8.1, so use the **`php81`** alias for every PHP command, and `php81 composer.phar` for Composer. Plain `php` is PHP 8.4, which only applies after the Laravel 13 upgrade (see `docs/`). Only the user switches WAMP's Apache PHP version, so ask them when a switch is needed.

```bash
# PHP deps (a composer.phar is committed at the repo root)
php81 composer.phar install

# Frontend (Vite 4 + Sass)
npm install
npm run dev        # Vite dev server with hot reload
npm run build      # outputs to public/build

# Database (MySQL by default; see .env.example)
php81 artisan migrate --seed       # seeds an admin user, options, and demo services/portfolio/timeline/team
php81 artisan migrate:fresh --seed

# Tests (PHPUnit 10, SQLite in-memory, so the local database is never touched)
php81 artisan test
php81 artisan test --filter=AuthenticationTest

# Code style (pint.json skips config/, which holds vendor-published files)
php81 vendor/bin/pint
```

Planned work lives in `docs/`, in this order: `project-review-fix-plan.md` (bug fixes, test suite; done), then `theme-licensing-plan.md` (MIT notices and credit for the Start Bootstrap themes, rebrand, self-hosted fonts; done), then `github-migration-plan.md` (move from Bitbucket to a public GitHub repo; commit messages are one-line `type: description`), then `laravel-13-upgrade-plan.md`, then `finish-project-plan.md` (unfinished features, built on Laravel 13 / Livewire 4).

The seeded admin login comes from `SEED_ADMIN_EMAIL` / `SEED_ADMIN_PASSWORD` in `.env` (default `admin@example.com` / `password`, read through `config('project.seed')`). The contact form recipient defaults to `ADMIN_EMAIL`. The local `.env` holds the developer's own values; never commit them.

## Architecture

### Two front ends, two asset pipelines
- **Public site:** `layouts/app.blade.php` (the `<x-app-layout>` component) loads `resources/agency/{scss,js}` through `@vite` and Bootstrap 5 from `public/vendor/bootstrap-5`.
- **Admin:** `layouts/admin.blade.php` (the `<x-admin-layout>` component) loads `resources/admin/{scss,js}` through `@vite`, plus Bootstrap 4, jQuery and DataTables from `public/vendor/`.
- Third-party JS/CSS is committed as static files under `public/vendor/` and loaded with `asset()`. Only the four entry points listed in `vite.config.js` go through Vite. Static images live in `public/img/{agency,admin}/` and are loaded with `asset('img/…')` in Blade and absolute `/img/…` URLs in SCSS (Vite leaves those untouched). Tests call `withoutVite()` (`tests/TestCase.php`), so they run without `npm run build`.

### Routing
- `routes/web.php` defines `/` (`HomeController`), then requires `auth.php` and `admin.php`, then registers a catch-all `/{page}` (`PageController`, which shows a published `Page` by slug, or a 404). New top-level routes must be registered **before** the catch-all.
- `routes/admin.php`: everything sits under the `auth` middleware with the `/admin` prefix. Resources are registered with `->only([...])` and cover only `index`/`create`/`edit` (or `show` for contacts). There are no `store`/`update`/`destroy` HTTP routes because Livewire components handle those writes.
- `routes/auth.php`: Volt routes for login, forgot-password and reset-password. Registration and email verification are commented out.

### Admin CRUD pattern
1. An admin controller extends `Admin\BaseController`. Its constructor builds the sidebar with `App\Project\AdminClass` (from `config('project.sidebar_nav')`) and shares it with all views as `$sidebarNav`.
2. The controller returns a plain Blade view in `resources/views/admin/<section>/`. That view sets `@section('title')` and `@section('slug')`; the slug picks the active sidebar item. It may also set `@section('dataTables', true)` to load DataTables, or `@section('action')` for header buttons. It then wraps its content in `<x-admin-layout>`.
3. Forms and mutations live in a Volt component at `resources/views/livewire/admin/<name>.blade.php`, embedded as `<livewire:admin.<name> :item="$item" />`. The component holds `store`/`update`/`destroy` methods. It reports success either by dispatching a browser event (e.g. `string-updated`), which `<x-common.message-success on="...">` listens for, or by setting `Session::flash('status')` and redirecting.
4. `livewire/admin/string.blade.php` (Translations/Strings) and `livewire/admin/options.blade.php` are the complete reference implementations. The other sections' `create`/`edit` actions return the shared `admin.common.not-implemented` view, and their Volt components are still empty stubs.

Adding an admin section means adding a sidebar entry in `config/project.php`, a resource route, a controller, the views and a Volt component.

Form markup goes through the anonymous components in `resources/views/components/common/` (`form-item`, `form-row`, `form-control`, `input-group`, `form-submit`, `row-actions`, `message-*`). `form-control` automatically adds `wire:model="<name>"` when it is given a `name`.

### Volt
`VoltServiceProvider` mounts `resources/views/livewire` and `resources/views/pages`. On the public side, the contact form is `livewire/pages/contact.blade.php`. It saves a `Contact` and mails `option('admin_email')`.

### Site options (global state)
`AppServiceProvider::register()` binds a lazy `project.options` singleton. The first time it's used, it reads the whole `options` table and merges the rows over the defaults in `config('project.options')`. Read values with the `option($name, $decode = false)` helper from `app/Project/Helpers.php` (loaded through `autoload.files` in `composer.json`); values stored as JSON, such as `social_links`, need `$decode = true`. Commands that never call `option()` don't need a database.

### Content models
`Service`, `Portfolio`, `Timeline`, `Team` and `Page` use the `HasPublishStatus` trait. The public site shows only `published()` rows, and `HomeController` sorts them by `position`. `Page` and `Translation` (key/replace pairs, managed as "Strings" in the admin) have admin screens. Translation values are not yet wired into `__()`.

## Quirks to know
- `Schema::defaultStringLength(191)` and `Paginator::useBootstrapFour()` are set globally.
- The contact form allows 3 messages per IP every 10 minutes (`RateLimiter`).
