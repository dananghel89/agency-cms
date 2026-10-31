# Upgrade plan: Laravel 10 → Laravel 13

Status: **Draft – not started**
Created: 2026-10-30 · Revised: 2026-10-30 (all optional items included) · Revised: 2026-10-31 (test suite, helpers fix and lazy options moved to the review plan)

> **Prerequisites:** [project-review-fix-plan.md](project-review-fix-plan.md) and [theme-licensing-plan.md](theme-licensing-plan.md) must be finished and merged into `master`, and [github-migration-plan.md](github-migration-plan.md) must be done (repo on GitHub, commits using the noreply identity) first. It delivers the SQLite test suite, the helpers fix, lazy options and the bug fixes. This plan assumes they are in place.

## 1. Goal and decisions

| Topic | Decision |
|---|---|
| Target framework | Laravel **13.x** (latest: 13.39 at time of writing) |
| Target PHP | **PHP 8.4** (8.4.26 in WAMP). Laravel 13 requires PHP ≥ 8.3, so it cannot run on 8.1 |
| App structure | **Migrate to the slim Laravel 11+ structure** (`bootstrap/app.php`, `bootstrap/providers.php`, no Kernels) |
| Tests | The app's own suite on **SQLite in-memory**, delivered by the review plan, is the safety net for every phase. Only the Livewire 4 phase changes it (`Volt::test` → `Livewire::test`) |
| Helpers / options | Already fixed by the review plan (`autoload.files`, lazy `sb.options` singleton) |
| Livewire | **Upgrade Livewire 3 → 4** and **remove Volt**: Volt components become native Livewire 4 single-file components |
| Frontend | **Upgrade the Vite toolchain** (Vite 4 → 8, laravel-vite-plugin 0.8 → 3.x, Sass to latest 1.x) |
| Out of scope | Bootstrap 4/5 version bumps; migrating SCSS `@import` to `@use`; building the empty admin create/edit screens (planned in [finish-project-plan.md](finish-project-plan.md), after this upgrade) |

### Current state → target

| Package | Current | Target |
|---|---|---|
| php | ^8.1 (WAMP php 8.1.34) | ^8.3 (run on 8.4.26) |
| laravel/framework | 10.43.0 | ^13.0 |
| laravel/sanctum | 3.3.3 | ^4.3 |
| laravel/tinker | ^2.8 | ^3.0 |
| livewire/livewire | 3.4.4 | ^4.4 |
| livewire/volt | 1.6.2 | **remove** (absorbed into Livewire 4) |
| barryvdh/laravel-debugbar (dev) | 3.9.2 | ^4.4 |
| laravel/breeze (dev) | 1.28.1 | **remove** (scaffolding already published; not used at runtime) |
| laravel/pint (dev) | ^1.0 | ^1.27 |
| laravel/sail (dev) | ^1.18 | **remove** (not used with WAMP) |
| nunomaduro/collision (dev) | ^7.0 | ^8.6 |
| phpunit/phpunit (dev) | 10.5.9 | ^12.5 |
| spatie/laravel-ignition (dev) | ^2.0 | ^2.12 |
| fakerphp/faker, mockery/mockery (dev) | ^1.9.1 / ^1.4.4 | ^1.23 / ^1.6 |
| vite / laravel-vite-plugin / sass (npm) | 4.5.3 / 0.8 / 1.76 | ^8.3 / ^3.2 / ^1.107 |

Node is 24.21 locally, which meets Vite 8's requirement (`^20.19 || >=22.12`).

## 2. Environment rules for this upgrade

- **PHP CLI aliases:** `php81` = PHP 8.1.34 (current project), `php` = PHP 8.4.26 (default in the terminal).
  - **Phase 0** (still Laravel 10) uses `php81` and `php81 composer.phar`.
  - **From Phase 2 on**, every command uses `php` (8.4).
- **Composer from Phase 2 on:** use the global `composer` (2.10.3, runs on PHP 8.4). The repo's `composer.phar` is the 2.2 LTS line, kept for the php81 setup. (Decided 2026-10-31.)
- **WAMP Apache PHP version:** only you switch it. The plan stops at explicit **⏸ CHECKPOINT** steps and asks you before (or right after) that is needed.
- **MySQL:** after the review plan, options load lazily, so `composer`/`artisan` no longer need MySQL to boot and tests use SQLite. The app itself (browser, `migrate`) still needs WAMP MySQL running.
- **Interim testing without touching WAMP:** during Phases 3–9, use the test suite (`php artisan test`) plus `php artisan serve` (PHP 8.4, http://127.0.0.1:8000) against the WAMP MySQL database. Apache stays on 8.1 until the final checkpoint.

> ⚠️ Once Phase 3 runs `composer update`, `vendor/` requires PHP ≥ 8.2. The WAMP site at `http://start-bootstrap.test` (Apache on PHP 8.1) **stops working** until Apache is switched to 8.4 (Phase 10) or you check out the `pre-laravel-13` tag (Phase 0) and run `php81 composer.phar install`.

## 3. Strategy

1. **Safety net first:** the review plan (prerequisite) leaves `master` with a green test suite on Laravel 10 / Livewire 3 / PHP 8.1. Phase 1 below only confirms it.
2. **Upgrade one major version at a time** (10 → 11 → 12 → 13) on PHP 8.4 CLI. Each phase must keep the suite green.
3. Then the **structure migration**, the **Livewire 4** upgrade and the **Vite** upgrade, as separate phases.
4. Each phase ends with `php artisan test`, the smoke checklist (section 5) and a commit pushed directly to `master`, only after you confirm it (I show the files and message first).

Each official upgrade guide is small (10–15 min), so if a phase turns the suite red, it's clear which change caused it.

### Phase overview

| # | Phase | PHP CLI | Stack after the phase |
|---|---|---|---|
| 0 | Preparation and baseline | php81 | L10 · LW3 + Volt · Vite 4 |
| 1 | Confirm the review plan is done (suite green) | php81 | L10 · LW3 + Volt · Vite 4 |
| 2 | PHP 8.4 CLI readiness, ⏸ Checkpoint A | php | – |
| 3 | Laravel 10 → 11 | php | L11 |
| 4 | Laravel 11 → 12 | php | L12 |
| 5 | Laravel 12 → 13 | php | L13 |
| 6 | Slim application structure | php | L13 (slim) |
| 7 | Livewire 3 → 4, remove Volt | php | L13 · LW4 |
| 8 | Vite toolchain | php | L13 · LW4 · Vite 8 |
| 9 | Final verification on `artisan serve` | php | – |
| 10 | ⏸ Checkpoint B: switch WAMP Apache to 8.4 | php | – |
| 11 | Wrap up | php | – |

---

## 4. Phases

### Phase 0 – Preparation and baseline (PHP 8.1, nothing changes)

1. Work directly on `master`, with no branch. Mark the starting point with `git tag pre-laravel-13` and `git push origin pre-laravel-13`; this is the rollback point.
2. Back up the database: WAMP MySQL → `mysqldump start_bootstrap > ../start_bootstrap-backup-YYYYMMDD.sql` (keep it outside the repo).
3. Record the baseline:
   - `php81 artisan --version` → `Laravel Framework 10.43.0`
   - `php81 artisan route:list > ../routes-before.txt`
   - `php81 artisan about > ../about-before.txt`
   - `php81 artisan test` → all green (the suite from the review plan, on SQLite in-memory).
4. Run the smoke checklist (section 5) on `http://start-bootstrap.test` and note anything already broken, so it isn't blamed on the upgrade.

### Phase 1 – Confirm the review plan is done (PHP 8.1, Laravel 10)

The former Phase 1 (helpers fix, lazy options, SQLite test suite) is now part of [project-review-fix-plan.md](project-review-fix-plan.md), Phases A–B. Here, only confirm:

1. `master` contains the finished review plan.
2. `phpunit.xml` uses SQLite in-memory. **Never run the tests before this is true**: with MySQL configured, `RefreshDatabase` wipes the local DB.
3. `php81 artisan test` → **all green on Laravel 10**. This is the reference for every later phase.
4. `composer.json` has `"files": ["app/Project/Helpers.php"]`. `option()` reads the lazy `sb.options` singleton. Neither helper name (`option`, `parse_args`) conflicts with the global functions that Laravel 13's `symfony/polyfill-php85` adds (`array_first`, `array_last`).
5. The tests use `test_*` names and PHP attributes (no doc-comment annotations), so they run on PHPUnit 10–12.

### Phase 2 – PHP 8.4 CLI readiness (no project changes)

1. `php -v` → 8.4.26.
2. `php -m` → confirm `pdo_mysql mbstring openssl intl fileinfo zip curl pdo_sqlite sqlite3 bcmath` (all confirmed present for CLI 8.4).
3. `composer --version` → 2.10.x.

**⏸ CHECKPOINT A – ask the user:** "Phase 3 makes the WAMP site (PHP 8.1) unusable until Apache is switched to 8.4. Proceed now, or switch WAMP Apache to 8.4 first?"

### Phase 3 – Laravel 10 → 11 (keep the old structure for now)

Laravel 11 still supports the L10 structure, so this phase changes only dependencies and config.

1. `composer.json`:
   - `"php": "^8.2"`
   - `laravel/framework: ^11.0`, `laravel/sanctum: ^4.0`, `laravel/tinker: ^2.9`, `livewire/livewire: ^3.4`, `livewire/volt: ^1.6`
   - dev: `nunomaduro/collision: ^8.1`, `phpunit/phpunit: ^11.0`, `spatie/laravel-ignition: ^2.4`, `barryvdh/laravel-debugbar: ^3.13`
   - remove `laravel/breeze` and `laravel/sail` from `require-dev`
2. `composer update -W`
3. **Sanctum 4:** migrations are no longer auto-loaded. `personal_access_tokens` already exists in `database/migrations`, so no publish is needed. Update `config/sanctum.php`:
   ```php
   'middleware' => [
       'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
       'encrypt_cookies' => Illuminate\Cookie\Middleware\EncryptCookies::class,
       'validate_csrf_token' => Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
   ],
   ```
4. Review the other L11 breaking changes. The checks below were done when this plan was written:
   - Column `->change()` migrations: **none** in the project.
   - `float`/`double`/`unsignedDecimal` columns: **none**.
   - Carbon `diffIn*` usage: **none** (only `Carbon::now()` in seeders).
   - Password rehash on login: the `users.password` column uses the default name, so no action is needed.
   - Per-second rate limiting: no custom `Limit` usage.
5. `php artisan migrate` (should report nothing to migrate) → `php artisan test` (green) → `php artisan serve` → smoke test.

### Phase 4 – Laravel 11 → 12

1. `composer.json`: `laravel/framework: ^12.0`, dev `phpunit/phpunit: ^11.0`.
2. `composer update -W`
3. Review L12 changes:
   - Carbon 3 is now required; this is safe (see Phase 3).
   - `local` disk default root: `config/filesystems.php` defines `local` explicitly, so no change.
   - `image` rule excludes SVG: there are no upload validations yet. Keep in mind for the future Portfolio/Team image uploads.
   - UUIDv7 / `HasUuids`: not used.
4. `php artisan test` (green) → `php artisan serve` → smoke test.

### Phase 5 – Laravel 12 → 13

1. `composer.json`:
   - `"php": "^8.3"`
   - `laravel/framework: ^13.0`, `laravel/tinker: ^3.0`, `laravel/sanctum: ^4.3`, `livewire/livewire: ^3.8`, `livewire/volt: ^1.11` (both still Livewire 3 here; Livewire 4 is Phase 7)
   - dev: `phpunit/phpunit: ^12.5`, `nunomaduro/collision: ^8.6`, `laravel/pint: ^1.27`, `barryvdh/laravel-debugbar: ^4.4`, `spatie/laravel-ignition: ^2.12`, `fakerphp/faker: ^1.23`, `mockery/mockery: ^1.6`
2. `composer update -W`
3. **Debugbar 4:** diff `config/debugbar.php` against the package's v4 config (`php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"` into a temporary copy) and adopt the new keys. Keep `DEBUGBAR_EDITOR=vscode`.
4. **PHPUnit 12:** `php vendor/bin/phpunit --migrate-configuration`. The suite already uses attributes (review plan, Phase B).
5. Review L13 changes:
   - **Request forgery (high impact):** `VerifyCsrfToken` was renamed to `PreventRequestForgery`. The only reference is `app/Http/Middleware/VerifyCsrfToken.php` (default, with an empty `$except`), which is deleted in Phase 6. Sanctum's `validate_csrf_token` key can point to `Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class`.
   - **Cache `serializable_classes` (medium):** the app does not cache PHP objects, so `false` is safe.
   - **Session `serialization` → `json`:** adopt it. The only session data is auth and flash strings. Active sessions are invalidated once, so you will need to log in again.
   - **Cache prefix / session cookie name:** `config/cache.php` and `config/session.php` define these explicitly today. If they are replaced with the L13 versions (Phase 6), the cookie changes from `start_bootstrap_session` to `start-bootstrap-session`, which again means one re-login. No action needed.
   - **`symfony/polyfill-php85` global functions:** no conflict (see Phase 1).
   - **Pagination view rename:** applies only to Bootstrap 3 views. The app uses `Paginator::useBootstrapFour()`, so no change.
   - `upsert`, `DELETE … JOIN`, queue events, polymorphic pivots: not used.
6. `php artisan --version` → `Laravel Framework 13.x` → `php artisan test` (green) → `php artisan serve` → smoke test.

### Phase 6 – Migrate to the slim application structure

Use the `laravel/laravel` v13 skeleton as the reference ([compare 10.x…13.x](https://github.com/laravel/laravel/compare/10.x...13.x)).

1. **`bootstrap/app.php`**: replace it with the `Application::configure()` style:
   ```php
   return Application::configure(basePath: dirname(__DIR__))
       ->withRouting(
           web: __DIR__.'/../routes/web.php',
           api: __DIR__.'/../routes/api.php',
           commands: __DIR__.'/../routes/console.php',
           health: '/up',
       )
       ->withMiddleware(function (Middleware $middleware): void {
           // was App\Http\Middleware\RedirectIfAuthenticated -> to_route('dashboard')
           $middleware->redirectUsersTo(fn () => route('dashboard'));
           // was App\Http\Middleware\Authenticate -> route('login') (also the framework default)
           $middleware->redirectGuestsTo(fn () => route('login'));
       })
       ->withExceptions(function (Exceptions $exceptions): void {
           //
       })->create();
   ```
   - `/up` is registered by the framework; check that `GET /up` returns 200 and isn't swallowed by the `/{page}` catch-all.
   - `routes/channels.php`: broadcasting is disabled (`BroadcastServiceProvider` is commented out), so do not register it; delete the file.
2. **`bootstrap/providers.php`**:
   ```php
   return [
       App\Providers\AppServiceProvider::class,
       App\Providers\VoltServiceProvider::class, // removed again in Phase 7
   ];
   ```
   Debugbar is auto-discovered, so remove the manual registration. A manual registration of a dev-only package would also break `composer install --no-dev`.
3. **Delete** (all are framework defaults with no project customizations, verified):
   - `app/Http/Kernel.php`, `app/Console/Kernel.php` (empty `schedule()`), `app/Exceptions/Handler.php` (empty `reportable`)
   - `app/Http/Middleware/*` (all 9 files). The only customization was `RedirectIfAuthenticated` → `dashboard`, now handled in `bootstrap/app.php`. The `TrimStrings` except-list equals the framework default.
   - `app/Providers/AuthServiceProvider.php` (no policies), `EventServiceProvider.php` (only `Registered → SendEmailVerificationNotification`, which Laravel 11+ auto-registers), `RouteServiceProvider.php` (`HOME` constant, `api` rate limiter and route loading move to `bootstrap/app.php`), `BroadcastServiceProvider.php`.
   - `tests/CreatesApplication.php`. Update `tests/TestCase.php` to the L13 version, which has no trait.
4. **`config/`**: replace the framework config files with the L13 versions (`php artisan config:publish --all --force`, then review the diff). Re-apply project values where they differ, and delete any config file that ends up identical to the framework default (L11+ falls back to the framework's). Keep `config/sb.php`, `config/livewire.php` and `config/debugbar.php`.
   - `config/app.php`: the `providers` and `aliases` arrays go away.
   - `config/database.php`: keep MySQL `utf8mb4` / `utf8mb4_unicode_ci` so new tables match the existing ones. Set `DB_CHARSET`/`DB_COLLATION` in `.env` if the L13 defaults differ.
5. **`.env` / `.env.example`** renames:
   - `CACHE_DRIVER` → `CACHE_STORE` (keep `file`; the L11+ default would be `database`)
   - `BROADCAST_DRIVER` → `BROADCAST_CONNECTION`
   - `MAIL_ENCRYPTION` → `MAIL_SCHEME`
   - add the L13 skeleton keys (`APP_LOCALE`, `APP_FALLBACK_LOCALE`, `APP_FAKER_LOCALE`, `APP_MAINTENANCE_DRIVER`, `BCRYPT_ROUNDS`, `LOG_STACK`, `SESSION_ENCRYPT`, `SESSION_PATH`, `SESSION_DOMAIN`, …). Keep `SESSION_DRIVER=file`, `QUEUE_CONNECTION=sync` and the MySQL settings.
6. **`composer.json`**: align `scripts` with the L13 skeleton (`pre-package-uninstall`, `test` script). Keep the `autoload.files` entry added by the review plan (R04).
7. **`phpunit.xml`**: rename the env keys (`CACHE_DRIVER` → `CACHE_STORE`, add `APP_MAINTENANCE_DRIVER`, `BCRYPT_ROUNDS`, `PULSE_ENABLED`/`NIGHTWATCH_ENABLED` as in the skeleton). Keep the SQLite in-memory settings.
8. `composer dump-autoload` → `php artisan optimize:clear` → `php artisan route:list > ../routes-after.txt`, then diff against `routes-before.txt`. Expected difference: `/up` added. Then `php artisan test` (green) → `php artisan serve` → smoke test.

### Phase 7 – Livewire 3 → 4 and remove Volt

Livewire 4 has single-file components built in. Volt's class-based syntax (`new class extends Component { … }; ?>` + Blade) is the same as Livewire 4's, so the Volt package is no longer needed. All 17 components in `resources/views/livewire/**` are class-based Volt, so none need rewriting.

1. **Dependencies:** `composer require livewire/livewire:^4.4 -W`, then `composer remove livewire/volt`.
2. **Config:** publish the v4 `config/livewire.php` (`php artisan livewire:publish --config`, review the diff) and set:
   - `component_locations` → `[resource_path('views/livewire')]` only. **Remove `resources/views/components`**: that folder holds this app's anonymous Blade components (`x-common.*`, `x-admin.*`, `x-navbar`, …), which should not be resolved as Livewire components.
   - `component_namespaces`: keep `layouts`. **Remove `pages`**: `resources/views/pages` holds plain Blade views (`home`, `default`), while the Livewire component `pages.contact` lives in `views/livewire/pages/`.
   - `make_command` → `type: 'sfc'`, `emoji: false` (matches the existing file naming).
   - `pagination_theme` → `'bootstrap'`.
   - `component_layout` stays at the default. All full-page components declare `#[Layout('layouts.guest')]`.
3. **Components** (17 files under `resources/views/livewire/`): replace `use Livewire\Volt\Component;` with `use Livewire\Component;`. Check that `#[Layout('layouts.guest')]` still resolves; otherwise switch to `#[Layout('layouts::guest')]`. `App\Livewire\Forms\LoginForm` (Livewire `Form`) and `App\Livewire\Actions\Logout` stay as they are.
4. **Routes:** in `routes/auth.php`, replace `Volt::route('login', 'auth.login')` with `Route::livewire('login', 'auth.login')` (and the same for forgot-password and reset-password), then remove `use Livewire\Volt\Volt`.
5. **Provider:** delete `app/Providers/VoltServiceProvider.php` and remove it from `bootstrap/providers.php`.
6. **Tests:** replace `Livewire\Volt\Volt` with `Livewire\Livewire`, `Volt::test(` with `Livewire::test(`, and `assertSeeVolt(` with `assertSeeLivewire(`.
7. **Template audit:** verified when this plan was written; recheck after the conversion.
   - All 23 `wire:model` bindings are on form inputs, so the change to ignore events bubbling up from child elements doesn't affect them. None use `.blur`/`.change` modifiers.
   - No `wire:model` value contains square brackets: options uses `social_links.facebook`; `social_links[facebook]` appears only in the HTML `name` attribute.
   - All `<livewire:… />` tags are self-closed.
   - No `wire:transition`, `$wire.$js`, `Livewire.hook('commit'|'request')` or `stream()` usage. `wire:confirm`, `wire:click` and `wire:submit` are unchanged.
   - `smart_wire_keys` now defaults to `true`. Check the social-links `@foreach` in `livewire/admin/options` still binds correctly.
8. **Endpoints:** Livewire URLs become `/livewire-{hash}/…` (derived from `APP_KEY`). Nothing in `.htaccess` or the app references `/livewire/`, and no Livewire assets are published under `public/vendor/`.
9. `php artisan optimize:clear` → `php artisan test` (green) → `php artisan serve` → smoke test, focusing on login/logout, the contact form, Strings CRUD, Options and Profile.

### Phase 8 – Vite toolchain upgrade

1. `package.json` devDependencies: `vite: ^8.3`, `laravel-vite-plugin: ^3.2`, `sass: ^1.107`, `axios: ^1.x` (latest). Keep the `bootstrap-4`/`bootstrap-5` aliases, jQuery, Chart.js, DataTables and Font Awesome versions unchanged.
2. Delete `node_modules` and `package-lock.json`, then `npm install`.
3. Read the laravel-vite-plugin upgrade notes (0.8 → 1.x → 2.x → 3.x) and update `vite.config.js` if the `input`/`refresh` options changed.
4. Bootstrap 4.6 / 5.2 SCSS and the `@import` syntax produce Dart Sass deprecation warnings (they still compile). Silence them in `vite.config.js`:
   ```js
   css: {
       preprocessorOptions: {
           scss: {
               quietDeps: true,
               silenceDeprecations: [/* IDs reported by the build, e.g. 'import', 'global-builtin', 'color-functions' */],
           },
       },
   },
   ```
   Only list the IDs the build actually prints; unknown IDs cause their own warning.
5. `npm run build`: check that the manifest and both CSS/JS bundles are generated in `public/build` and that `Vite::asset()` image paths resolve. Then `npm run dev` with `php artisan serve` → HMR works on both the site and the admin.

### Phase 9 – Final verification on `artisan serve`

1. `composer validate`, `composer audit`, `npm audit` (information only; record the findings).
2. `php artisan test` (green), `php artisan about`, `php artisan route:list` diff vs baseline.
3. Full smoke checklist (section 5) on `php artisan serve` with `npm run build` assets.

### Phase 10 – Switch WAMP Apache to PHP 8.4

**⏸ CHECKPOINT B – ask the user** to switch WAMP → PHP → Version → **8.4.26** and restart services. (`phpForApache.ini` for 8.4 already enables `curl fileinfo intl mbstring openssl pdo_mysql pdo_sqlite sqlite3 zip`, so no ini change should be needed.)

Then:
1. `php artisan optimize:clear`
2. Full smoke checklist (section 5) on `http://start-bootstrap.test`.
3. `php artisan about` → PHP 8.4.26, Laravel 13.x, Livewire 4.x.

### Phase 11 – Wrap up

1. Update `CLAUDE.md`:
   - commands now use `php` (8.4) and `composer`; tests run on SQLite in-memory
   - `bootstrap/app.php` / `bootstrap/providers.php` replace the Kernels and providers
   - Livewire 4 single-file components replace Volt (`Route::livewire`, `Livewire::test`)
   - Vite 8
   - remove the `php81` note
2. `./vendor/bin/pint --test` for information only (no mass reformat in this upgrade).
3. Final review of `git diff pre-laravel-13 master`, with your approval. Then delete the tag (`git tag -d pre-laravel-13`, `git push origin :refs/tags/pre-laravel-13`).

---

## 5. Smoke-test checklist (run after every phase)

Public site:
- [ ] `/` renders all sections (services, portfolio + modals, timeline, team, clients), with styles and images loading
- [ ] Contact form: validation errors appear; a valid submit shows the success message, creates a `contacts` row and logs the mail (`MAIL_MAILER=log` → `storage/logs`)
- [ ] `/some-slug` reaches the `/{page}` catch-all (currently an empty response, the same as before)

Auth:
- [ ] `/admin` as a guest → redirects to `/login`
- [ ] Log in with the seeded admin → lands on the dashboard
- [ ] `/login` while logged in → redirects to the dashboard
- [ ] Forgot-password page renders
- [ ] Logout works

Admin:
- [ ] Dashboard counters; the sidebar active state follows `@section('slug')`
- [ ] Every list page loads, with the DataTables JS/CSS working
- [ ] Strings: create → edit → update → delete (Livewire flash/dispatch messages)
- [ ] Options: save site title, admin email and social links; values show on the public site
- [ ] Profile: update the name/email and the password
- [ ] Debugbar appears (`APP_DEBUG=true`)

CLI:
- [ ] `php artisan test` → all green
- [ ] `php artisan about`, `php artisan route:list`, `php artisan migrate:status`

## 6. Rollback

- Code: `git revert --no-edit pre-laravel-13..master` (a new commit, so no force-push), then `php81 composer.phar install`, then `npm ci`. To only test the old state, run `git checkout pre-laravel-13`.
- Database: the upgrade adds no migrations. If anything goes wrong, restore the Phase 0 dump.
- WAMP: switch Apache back to PHP 8.1.34.
- Each phase is its own commit, so you can also revert only the phases after the last good one.

## 7. Known issues and risks

| Item | Impact | Handling |
|---|---|---|
| Review plan not finished | No safety net; risk of running tests against MySQL | Phase 1 blocks the upgrade until the review plan is merged |
| WAMP site down between Phase 3 and Phase 10 | No local Apache site during the upgrade | Use `php artisan serve`; Checkpoint A |
| Livewire 4 resolving names from `views/components` / the `pages::` namespace | Wrong component or view picked up | Phase 7 step 2 restricts `component_locations` and drops the `pages` namespace |
| Session/cookie changes in L13 | One forced re-login | Expected |
| Sass deprecation noise from Bootstrap 4/5 | Build warnings only | `quietDeps` + `silenceDeprecations` (Phase 8) |

Bugs found during the upgrade (fill in while implementing; pre-existing bugs belong in the review plan, section 5):

| Test | Bug | Decision |
|---|---|---|
| – | – | – |

## 8. References

- Laravel upgrade guides: [11.x](https://laravel.com/docs/11.x/upgrade), [12.x](https://laravel.com/docs/12.x/upgrade), [13.x](https://laravel.com/docs/13.x/upgrade)
- Livewire upgrade guide 3 → 4: https://livewire.laravel.com/docs/4.x/upgrading
- Skeleton diff: https://github.com/laravel/laravel/compare/10.x...13.x
- Package constraints verified on Packagist (2026-10-30):
  - laravel/framework 13.39 (php ^8.3); laravel/laravel skeleton v13.11.0
  - livewire/livewire 3.8.11 and 4.4.8 (both allow L13); livewire/volt 1.11.2 (allows LW ^3.6.1|^4.0; not needed after Phase 7)
  - laravel/sanctum 4.3.3; barryvdh/laravel-debugbar 4.4.5
