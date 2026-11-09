# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

A Laravel 13 app (PHP ^8.3, run on 8.4) that wraps two Start Bootstrap themes. One is a public "Agency" one-page site on Bootstrap 5. The other is an "SB Admin 2" CMS on Bootstrap 4 that manages the site's content. Interactive UI uses Livewire 4 single-file components. Auth started as Laravel Breeze (Volt stack), was trimmed down, and its components are now plain Livewire 4. Project-wide settings live in `config/project.php` (`config('project.…')`).

The app uses the slim Laravel 11+ structure: `bootstrap/app.php` holds routing, middleware (logged-in users are redirected to `dashboard`, guests to `login`) and exception settings, and `bootstrap/providers.php` lists the providers. There are no HTTP/console kernels or app middleware classes. `config/` holds only `project.php`, `livewire.php` and `debugbar.php`; everything else uses the framework defaults, configured through `.env`.

## Commands

Local environment: WAMP on Windows with PHP 8.4. Use plain `php` (8.4) and the global `composer` (2.10). Only the user switches WAMP's Apache PHP version, so ask them when a switch is needed.

```bash
# PHP deps
composer install

# Frontend (Vite 8 + laravel-vite-plugin 3 + Sass)
npm install
npm run dev        # Vite dev server with hot reload
npm run build      # outputs to public/build

# Database (MySQL by default; see .env.example)
php artisan migrate --seed       # seeds an admin user, options, and demo services/portfolio/timeline/team
php artisan migrate:fresh --seed

# Tests (PHPUnit 12, SQLite in-memory, so the local database is never touched)
php artisan test
php artisan test --filter=AuthenticationTest

# Code style (pint.json skips config/, which holds vendor-published files)
php vendor/bin/pint
```

Bootstrap 4/5 SCSS still uses `@import`, so `vite.config.js` silences the Dart Sass deprecations the build reports (`quietDeps` + `silenceDeprecations`).

Planned work lives in `docs/`. These plans are local working notes and are gitignored on purpose; never add them with `git add -f`. The order is: `project-review-fix-plan.md` (bug fixes, test suite; done), then `theme-licensing-plan.md` (MIT notices and credit for the Start Bootstrap themes, rebrand, self-hosted fonts; done), then `github-migration-plan.md` (move from Bitbucket to a public GitHub repo; done), then `laravel-13-upgrade-plan.md` (done), then `finish-project-plan.md` (unfinished features, built on Laravel 13 / Livewire 4).

The repo is public at `https://github.com/dananghel89/agency-cms` (`origin`, branch `master`). Commits use the GitHub noreply address automatically (`~/.gitconfig` `includeIf` on the GitHub remote), and commit messages are one-line `type: description`.

The seeded admin login comes from `SEED_ADMIN_EMAIL` / `SEED_ADMIN_PASSWORD` in `.env` (default `admin@example.com` / `password`, read through `config('project.seed')`). The contact form recipient defaults to `ADMIN_EMAIL`. The local `.env` holds the developer's own values; never commit them.

## Architecture

### Two front ends, two asset pipelines
- **Public site:** `layouts/app.blade.php` (the `<x-app-layout>` component) loads `resources/agency/{scss,js}` through `@vite` and Bootstrap 5 from `public/vendor/bootstrap-5`.
- **Admin:** `layouts/admin.blade.php` (the `<x-admin-layout>` component) loads `resources/admin/{scss,js}` through `@vite`, plus Bootstrap 4, jQuery and DataTables from `public/vendor/`.
- Third-party JS/CSS is committed as static files under `public/vendor/` and loaded with `asset()`. Only the four entry points listed in `vite.config.js` go through Vite. Static images live in `public/img/{agency,admin}/` and are loaded with `asset('img/…')` in Blade and absolute `/img/…` URLs in SCSS (Vite leaves those untouched). Tests call `withoutVite()` (`tests/TestCase.php`), so they run without `npm run build`.

### Routing
- `routes/web.php` defines `/` (`HomeController`), then requires `auth.php` and `admin.php`, then registers a catch-all `/{page}` (`PageController`, which shows a published `Page` by slug, or a 404). New top-level routes must be registered **before** the catch-all.
- `routes/admin.php`: everything sits under the `auth` middleware with the `/admin` prefix. Resources are registered with `->only([...])` and cover only `index`/`create`/`edit` (or `show` for contacts). There are no `store`/`update`/`destroy` HTTP routes because Livewire components handle those writes.
- `routes/auth.php`: `Route::livewire()` full-page routes for login, forgot-password and reset-password. Registration and email verification are commented out.
- `bootstrap/app.php` also loads `routes/api.php` (Sanctum's `/api/user`, `api` rate limiter defined in `AppServiceProvider`), `routes/console.php`, and the framework's `/up` health check.

### Admin CRUD pattern
1. An admin controller extends `Admin\BaseController`. Its constructor builds the sidebar with `App\Project\AdminClass` (from `config('project.sidebar_nav')`) and shares it with all views as `$sidebarNav`.
2. The controller returns a plain Blade view in `resources/views/admin/<section>/`. That view sets `@section('title')` and `@section('slug')`; the slug picks the active sidebar item. It may also set `@section('dataTables', true)` to load DataTables, or `@section('action')` for header buttons. It then wraps its content in `<x-admin-layout>`.
3. Forms and mutations live in a Livewire single-file component (`new class extends Livewire\Component { … }; ?>` followed by Blade) at `resources/views/livewire/admin/<name>.blade.php`, embedded as `<livewire:admin.<name> :item="$item" />`. The component holds `store`/`update`/`destroy` methods. It reports success either by dispatching a browser event (e.g. `string-updated`), which `<x-common.message-success on="...">` listens for, or by setting `Session::flash('status')` and redirecting.
4. `livewire/admin/string.blade.php` (Translations/Strings) and `livewire/admin/options.blade.php` are the complete reference implementations. The other sections' `create`/`edit` actions return the shared `admin.common.not-implemented` view, and their Livewire components are still empty stubs.

Adding an admin section means adding a sidebar entry in `config/project.php`, a resource route, a controller, the views and a Livewire component.

Form markup goes through the anonymous components in `resources/views/components/common/` (`form-item`, `form-row`, `form-control`, `input-group`, `form-submit`, `row-actions`, `message-*`). `form-control` automatically adds `wire:model="<name>"` when it is given a `name`.

### Livewire
`config/livewire.php` limits `component_locations` to `resources/views/livewire`: `resources/views/components` holds anonymous Blade components (`x-common.*`, `x-admin.*`, …), and `resources/views/pages` holds plain Blade views, so neither is resolved as Livewire. New components are single-file (`make_command.type = sfc`), and pagination uses the Bootstrap theme. Tests use `Livewire::test()` and `assertSeeLivewire()`. On the public side, the contact form is `livewire/pages/contact.blade.php`. It saves a `Contact` and mails `option('admin_email')`.

### Site options (global state)
`AppServiceProvider::register()` binds a lazy `project.options` singleton. The first time it's used, it reads the whole `options` table and merges the rows over the defaults in `config('project.options')`. Read values with the `option($name, $decode = false)` helper from `app/Project/Helpers.php` (loaded through `autoload.files` in `composer.json`); values stored as JSON, such as `social_links`, need `$decode = true`. Commands that never call `option()` don't need a database.

### Content models
`Service`, `Portfolio`, `Timeline`, `Team` and `Page` use the `HasPublishStatus` trait. The public site shows only `published()` rows, and `HomeController` sorts them by `position`. `Page` and `Translation` (key/replace pairs, managed as "Strings" in the admin) have admin screens. Translation values are not yet wired into `__()`.

## Quirks to know
- `Schema::defaultStringLength(191)` and `Paginator::useBootstrapFour()` are set globally.
- The contact form allows 3 messages per IP every 10 minutes (`RateLimiter`).
