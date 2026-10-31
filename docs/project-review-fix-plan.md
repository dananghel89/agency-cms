# Project review and fix plan

Status: **In progress** – Phase A done (af5bcff), Phase B done
Created: 2026-10-31
Runs **first**. Then [theme-licensing-plan.md](theme-licensing-plan.md), then [github-migration-plan.md](github-migration-plan.md), then [laravel-13-upgrade-plan.md](laravel-13-upgrade-plan.md), then [finish-project-plan.md](finish-project-plan.md). The upgrade plan's Phase 1 (helpers fix, lazy options, test suite) moved into this plan.

## 1. Scope and decisions

| Topic | Decision |
|---|---|
| Stack during this plan | Unchanged: Laravel 10.43, Livewire 3 + Volt, PHP **8.1** (`php81`), Vite 4 |
| What gets fixed | Everything that is **wrong**: bugs, security issues, broken config, dead UI, code style |
| Unfinished features | **Not built here.** They are made to *fail cleanly* (404 / "not implemented yet" page / hidden buttons) and listed in the backlog (section 6) |
| Database | Local only, data disposable. Schema fixes go **directly into the original migrations**, followed by `migrate:fresh --seed` (after a backup) |
| Way of working | Every fix starts with a failing test, then the code change, then a green suite. Each phase is one commit (with your approval) |
| Commit messages | One line, `type: description` (`fix:`, `feat:`, `test:`, `refactor:`, `chore:`, `docs:`, `style:`). The GitHub migration that follows keeps these messages as they are |

## 2. Environment rules

- PHP: **`php81`** for every command. Composer: **`php81 composer.phar`**.
- WAMP Apache stays on PHP 8.1 for this whole plan; no version switch is needed.
- > ⛔ **Do not run `php81 artisan test` until step A2 is done.** Today `phpunit.xml` has no test database configured, so tests run against the real `start_bootstrap` MySQL database. The existing tests use `RefreshDatabase`, which **wipes it** (finding R01).
- Back up the database before Phase A: `mysqldump start_bootstrap > ../start_bootstrap-backup-YYYYMMDD.sql`.

## 3. Findings

How each finding was confirmed:
- **Reproduced:** confirmed by running it in isolation on 2026-10-31. These checks used in-memory SQLite and the in-memory mail driver; no MySQL data was touched.
- **Code:** certain from reading the code.
- **Browser:** must be confirmed in the browser during the fix.

Two suspicions were checked and **dropped** because they turned out not to be bugs:
- The contact email's `$message` variable renders correctly.
- Options saves the `social_links` array correctly, as JSON.

### Critical

| ID | Area | Location | Problem | Confirmed by |
|---|---|---|---|---|
| R01 | Tests | `phpunit.xml:24-25`, `tests/Feature/Auth/*` | The SQLite test DB lines are commented out, so tests use the `.env` MySQL database. Five test classes use `RefreshDatabase`, so running the suite **drops all local data** | Code |

### High

| ID | Area | Location | Problem | Confirmed by |
|---|---|---|---|---|
| R02 | Admin › Strings | `livewire/admin/string.blade.php:14-16, 42` | The `unique` rule has no `ignore()` on update, so saving a string without changing its key fails: *"The key has already been taken."* Editing only the replacement text is impossible | Reproduced |
| R03 | Boot | `AppServiceProvider.php:29-32` | `boot()` runs `Options::all()` on every boot. On an empty DB **every artisan command fails** (`no such table: options`), including `migrate`, so a fresh install is impossible. It also makes every `composer`/`artisan` call need MySQL, and blocks SQLite tests | Reproduced |
| R04 | Boot | `AppServiceProvider.php:17` | `require_once …/helpers.php`, but the file is `Helpers.php`. This is a fatal error on case-sensitive filesystems (Linux hosting) | Code |
| R05 | Tests | `tests/Feature/Auth/*`, `tests/Feature/ProfileTest.php`, `tests/Feature/ExampleTest.php` | Stale Breeze tests reference things that don't exist here: `pages.auth.login`, `layout.navigation`, `/dashboard`, `/profile`, `RouteServiceProvider::HOME`, register/verify routes. The project has no usable tests | Code |

### Medium

| ID | Area | Location | Problem | Confirmed by |
|---|---|---|---|---|
| R06 | Public › Home | `HomeController.php:17-20` | Ignores the `status` column, so `draft` services, portfolio, timeline and team items are shown publicly. It also sorts in PHP after loading every row | Code |
| R07 | Admin › Lists | `layouts/admin.blade.php:25-26, 82-84`, `resources/admin/js/scripts.js` | The DataTables JS/CSS is loaded on every list page but **never initialized** (there's no `.DataTable()` call), so there is no search, sorting or paging. `data-orderable` has no effect | Code |
| R08 | Admin › Lists | `components/common/row-actions.blade.php:17-21` + 7 index views | The delete buttons are plain `<button>` elements with no handler; clicking them does nothing | Code |
| R09 | Admin › CRUD | Page/Service/Portfolio/Timeline/Team/User controllers + 12 empty `create`/`edit` views | The create/edit pages render a **blank document** (empty view, no layout). `edit/{id}` returns 200 for any id, including ids that don't exist | Code |
| R10 | Public › Pages | `app/Http/Controllers/PageController.php`, `routes/web.php:23` | The `/{page}` catch-all returns an **empty 200** for any URL (e.g. `/anything`). There are no real 404s on the public site | Code |
| R11 | DB › Pages | `2023_12_17_135009_create_pages_table.php`, `Page.php` | Column typo `descrition` (should be `description`) in the migration and in `$fillable` | Code |
| R12 | Security › XSS | `Team.php` (`getSocialLinksHtmlAttribute`), `admin/team/index.blade.php` | Social-link names and URLs are concatenated into HTML without escaping and printed with `{!! !!}`. A crafted link (e.g. `"><script>…`) runs in the admin | Code |
| R13 | Security › Contact | `livewire/pages/contact.blade.php` | No rate limiting on the public contact form; every submit writes a DB row and sends an email (spam/mail flooding). The `spam` status exists but is never used | Code |
| R14 | Admin › Options | `livewire/admin/options.blade.php:34-52`, `DashboardController::options` | `update()` only updates option **rows that already exist** (found by id). If a row is missing (no seeder, or a new option), the value is **silently not saved**. One `find()` query per option | Code |
| R15 | Auth › Reset | `livewire/auth/reset-password.blade.php:74, 82` | `wire:submit="resetPassword"` is on the wrapper `<div>`, not on the `<form>`, so it relies on event bubbling. It may fall back to a native GET submit (password in the URL) | Browser |

### Low

| ID | Area | Location | Problem | Confirmed by |
|---|---|---|---|---|
| R16 | Auth › Logout | `livewire/auth/logout.blade.php:15` | After logout it redirects to `dashboard` (protected), which redirects again to `/login` | Code |
| R17 | UI | `components/common/feedback-valid.blade.php:5` | Copy-paste error: it uses the `invalid-feedback` class | Code |
| R18 | UI | `components/common/message-flash.blade.php:12, 23-24` | Duplicate `x-show` attribute, plus Alpine v2 syntax (`x-show.transition…`), which Alpine 3 (bundled with Livewire 3) ignores | Code |
| R19 | UI | `components/common/form-control.blade.php:16-19, 38, 45` | Adds a `type` attribute to `<textarea>` and `<select>` (invalid HTML) | Code |
| R20 | Public › Footer/Team | `components/social-links.blade.php` | Hard-codes 3 networks instead of using `Options::socialNetworks()`, and reads `$item['facebook']` etc. without `isset` (*Undefined array key* if a network is missing) | Code |
| R21 | Admin › Dashboard | `DashboardController.php:19-23` | `Model::all()->count()` loads every row just to count it | Code |
| R22 | Public › Contact | `livewire/pages/contact.blade.php` | The `lowercase` email rule **rejects** addresses typed with capitals instead of normalizing them | Code |
| R23 | Mail | `mail/contact-form.blade.php:4` | Prints literal quotes: `**Subject:** 'New contact message'` | Code |
| R24 | Admin › Lists | `admin/{strings,services,pages,…}/index.blade.php` | Copy-pasted card header "All Contacts" on non-contact lists (hidden with `d-none`, but wrong) | Code |
| R25 | Public › Layout | `layouts/app.blade.php:35` | Loads `jquery.slim`, which neither Bootstrap 5 nor the agency scripts use | Code |
| R26 | Seeders | `database/seeders/*`, `Options::userEmails()` | Rows are inserted without `updated_at`; dead code `unset($item['array'])` in `OptionsSeeder`; admin credentials (`admin@example.com` / `password`) are hard-coded | Code |
| R27 | Admin › Views | `resources/views/admin/porfolio/` | Folder name typo (`porfolio`) | Code |
| R28 | Dead code | `string.blade.php` (`$delete` branch, `//` placeholder), `contact.blade.php` (`wasRecentlyCreated` else-branch can't happen), unused imports (`RouteServiceProvider` in login and `VerifyEmailController`, `View` in `PageController`) | Unreachable or unused code | Code |
| R29 | Code style | 24 files | `pint --test` (Laravel preset) flags unused/unordered imports, brace placement, spacing | Reproduced |
| R30 | Docs | `README.md` | The stock Laravel README; nothing about this project | Code |

All Blade views compile (`php81 artisan view:cache` succeeded), so there are no Blade syntax errors.

## 4. Phases

### Phase A – Safety first (no behaviour change)

1. Work directly on `master`: commit and push each step only after you confirm it (I show the files and message first), with no feature branch. Back up the database (section 2).
2. **R01:** in `phpunit.xml`, enable `<env name="DB_CONNECTION" value="sqlite"/>` and `<env name="DB_DATABASE" value=":memory:"/>`. From now on, running tests is safe.
3. **R04:** in `composer.json`, set `"autoload": { "files": ["app/Project/Helpers.php"], … }` and remove the `require_once` from `AppServiceProvider::register()`. Then `php81 composer.phar dump-autoload`.
4. **R03:** make options lazy.
   - In `AppServiceProvider::register()`:
     ```php
     $this->app->singleton('sb.options', fn () => parse_args(
         Options::query()->pluck('value', 'name')->all(),
         config('sb.options')
     ));
     ```
   - Remove the `$GLOBALS['options']` block from `boot()`.
   - `option()` reads `data_get(app('sb.options'), $name)` and keeps `$decode`.
5. Check: `DB_CONNECTION=sqlite DB_DATABASE=:memory: php81 artisan migrate:status` no longer throws `no such table: options`, and the WAMP site works exactly as before.

### Phase B – Test suite for the current, correct behaviour (R05)

1. Delete the stale Breeze tests: `tests/Feature/Auth/{EmailVerification,PasswordConfirmation,Registration,PasswordUpdate}Test.php`, `tests/Feature/ProfileTest.php` and `tests/Feature/ExampleTest.php`. `AuthenticationTest` and `PasswordResetTest` are rewritten below.
2. Add factories for `Service`, `Portfolio`, `Timeline`, `Team`, `Page`, `Contact`, `Translation`, `Options`, built from each model's `$fillable`.
3. Conventions: `test_*` method names and PHP attributes (`#[DataProvider]`), not annotations, so they work on PHPUnit 10–12 for the upgrade.
4. Write the tests below. In this phase they cover only behaviour that is **already correct**, so they pass.

| Test | Covers |
|---|---|
| `Feature/Auth/AuthenticationTest` | `/login` renders `auth.login`; login (`form.email`/`form.password`) redirects to the dashboard; a wrong password fails; guest `/admin` → `/login`; logged-in `/login` → dashboard; `auth.logout` ends the session |
| `Feature/Auth/PasswordResetTest` | Forgot-password renders; `sendPasswordResetLink` sends `ResetPassword`; the reset screen renders; `resetPassword` with a valid token works |
| `Feature/Admin/AdminPagesTest` | Every admin **index** route plus `dashboard`, `profile`, `options`, `contacts.show`, `strings.create`, `strings.edit`: 200 when logged in, redirect when a guest |
| `Feature/Admin/StringsTest` | `store` (validation, duplicate key rejected, redirect); `update` with a **changed** key; `destroy` |
| `Feature/Admin/OptionsTest` | `update` persists `site_title`, `admin_email` and `social_links` (JSON) when the rows exist; validation |
| `Feature/Admin/ProfileTest` | `updateProfileInformation`; `updatePassword` (a wrong current password fails) |
| `Feature/HomePageTest` | `/` shows published seeded content; `<title>` uses `option('site_title')` |
| `Feature/ContactFormTest` | Validation; a valid submit creates a `contacts` row and sends `ContactForm` to `option('admin_email')`; no mail when `admin_email` is empty |
| `Feature/OptionHelperTest` | DB values override config defaults; defaults apply with an empty table (R03); `option('social_links', true)` decodes |

5. `php81 artisan test` → all green.

### Phase C – Functional and security fixes (High/Medium)

Each item: add a failing test → fix → green.

| ID | Test first | Fix |
|---|---|---|
| R02 | `StringsTest`: update with an **unchanged** key succeeds | Build the rules in a method: `Rule::unique(Translation::class, 'key')->ignore($this->item?->id)` |
| R06 | `HomePageTest`: `draft` items are not shown | Add `scopePublished()` (`where('status', 'publish')`) to Service/Portfolio/Timeline/Team. `HomeController` uses `::published()->orderBy('position')->get()` |
| R07 | – (JS) | In `resources/admin/js/scripts.js`: `if ($.fn.DataTable) { $('#dataTable').DataTable(); }`. Check sorting/search on every list and that the actions column isn't sortable |
| R08 | – (UI) | Remove the `'delete' => …` entries from the 7 index views so no dead button renders. `row-actions` keeps supporting `delete` for later (backlog) |
| R09 | `AdminPagesTest`: `*.edit` with an unknown id → 404; `*.create`/`*.edit` render inside the admin layout | Edit actions use `findOrFail($id)`. Replace the 12 empty views with one shared `admin/common/not-implemented.blade.php` (admin layout, title and slug set, "Not implemented yet" message, back link) |
| R10 | `PublicPageTest`: unknown slug → 404; `draft` page → 404; `publish` page → 200 with title and content | `PageController`: `Page::where('slug', $slug)->where('status', 'publish')->firstOrFail()` → `pages.default` with `$page`. The template shows the title and the escaped content (`nl2br(e())`). Remove the unused import |
| R11 | `Page` factory/test uses `description` | Rename the column in the original migration and in `Page::$fillable`. Then `php81 artisan migrate:fresh --seed` on the local DB (backup from Phase A) |
| R12 | `TeamTest`: a link containing `"><script>` comes out escaped in the admin team list | Escape the name and URL (`e()`) in the accessor, or render the links in Blade from the `social_links` array and drop the accessor/`$appends`. Accept only `http(s)` URLs |
| R13 | `ContactFormTest`: the 4th submit within 10 minutes from the same IP is rejected with an error, and no row or mail is created | `RateLimiter::attempt('contact:'.request()->ip(), 3, …, 600)` in `submit()`; show a translated error message |
| R14 | `OptionsTest`: `update` with an **empty** options table creates the rows | Loop over the known option names and use `Options::updateOrCreate(['name' => $name], ['value' => …])` (JSON-encode arrays). `mount()` reads `option()` values, so config defaults show up. `social_links.*` → `nullable` |
| R15 | `PasswordResetTest` (Livewire level) + **browser check** | Move `wire:submit="resetPassword"` from the wrapper `<div>` to the `<form>`. In the browser, the reset completes with no `?password=` in the URL |

`php81 artisan test` → green after every row.

### Phase D – Low-priority fixes and cleanup

| ID | Fix |
|---|---|
| R16 | Logout → `redirectRoute('login')` (test: logout redirects to `/login`) |
| R17 | `feedback-valid` → `valid-feedback` |
| R18 | `message-flash`: a single `x-show="shown"` + `x-transition:leave…` (Alpine 3 syntax) |
| R19 | `form-control`: add `type` only for `<input>` |
| R20 | `social-links`: loop over `Options::socialNetworks()` and render only non-empty `$item[$name] ?? null` |
| R21 | Dashboard: `Model::count()` |
| R22 | Contact: lowercase/trim the email before validating, and drop the `lowercase` rule |
| R23 | Mail template: remove the quotes |
| R24 | Correct the card headers ("All Strings", "All Services", …) |
| R25 | Remove `jquery.slim` from `layouts/app.blade.php`; check the public site (navbar collapse, scrollspy, portfolio modals) |
| R26 | Seeders: set `updated_at`; remove the dead `unset`. Move the admin login and emails to `.env` with **neutral defaults** (needed for the public GitHub repo):<br>- `SEED_ADMIN_EMAIL` (default `admin@example.com`) and `SEED_ADMIN_PASSWORD` (default `password`)<br>- `SB_ADMIN_EMAIL` for `config('sb.options.admin_email')`, default `admin@example.com`<br>- `Options::userEmails()` reads the same settings<br>Document the keys in `.env.example`, and put your real values only in your local `.env`. Update the seeded-login note in `CLAUDE.md` |
| R27 | Rename `admin/porfolio` → `admin/portfolio` and update `PortfolioController` |
| R28 | Remove the dead code and unused imports listed in R28 |
| R30 | Replace the README with a short project README (what it is, requirements, setup, `php81` commands, test command, seeded login note) |

### Phase E – Code style (R29)

1. `php81 vendor/bin/pint` (default Laravel preset) in **its own commit**, with no logic changes, so the diff is easy to review.
2. `php81 vendor/bin/pint --test` → clean.

### Phase F – Verification

1. `php81 artisan migrate:fresh --seed` (local, disposable), then `php81 artisan test` → all green.
2. `php81 artisan view:cache` → OK, then `php81 artisan view:clear`.
3. `php81 artisan route:list`: same routes as before.
4. `npm run build` → OK.
5. Browser smoke test on `http://start-bootstrap.test`:
   - **Public site:** home (only published items), contact form (validation, success, rate limit), `/unknown` → 404 page.
   - **Auth:** login, forgot/reset password (no password in the URL), logout → `/login`.
   - **Admin:**
     - dashboard counters
     - every list (DataTables search/sort, no dead delete buttons)
     - Strings create/edit (unchanged key)/delete
     - Options save (also after deleting the option rows)
     - Profile
     - "Not implemented yet" pages
     - `edit/999` → 404

### Phase G – Wrap up

1. Update `CLAUDE.md`:
   - tests run on SQLite in-memory (safe)
   - `option()` uses the lazy `sb.options` singleton
   - helpers are loaded via `autoload.files`
   - remove the fixed quirks (helpers case, `porfolio`, stale tests)
2. Mark the upgrade plan's Phase 1 as done by this plan (already reflected there).
3. Final review of all commits since the start of the plan, with your approval. Then the theme-licensing plan can start.

## 5. Bugs discovered while implementing

Add any new findings here (ID, location, problem, decision). Fix them only if they are small and you approve; otherwise move them to the backlog.

| ID | Location | Problem | Decision |
|---|---|---|---|
| – | – | – | – |

## 6. Backlog (unfinished features – not part of this plan)

These items are planned in [finish-project-plan.md](finish-project-plan.md), which runs after the Laravel 13 upgrade.

| Feature | Notes |
|---|---|
| Create/edit screens for Pages, Services, Portfolio, Timeline, Team, Users | Livewire components following the Strings pattern; the stubs in `livewire/admin/{page,service,portfolio,timeline,team,user}.blade.php` already exist |
| Delete actions on lists | `row-actions` already supports `delete`. Needs a Livewire action + confirmation |
| Images for Portfolio, Team, Timeline | The views read `->image`, but there is no column, no `$fillable` entry and no upload. Needs a migration, storage disk and validation (`image` excludes SVG from Laravel 12) |
| Pages front end | Templates (`template` column), SEO description, admin editor |
| Translations | The `translations` table (Strings) is managed but never used by `__()`. Needs a loader |
| Users and roles | Any authenticated user has full admin; the "Permissions" and "Logs" menu items are commented out |
| Contact spam workflow | The `spam` status exists but has no UI or detection |
| Contact phone format | `numeric|digits:9` allows only 9-digit local numbers (no `+34…`, spaces). Decided: international format, done in the finish plan (Phase 7) |
| `@import` → `@use` in SCSS | Do it after the Vite upgrade |

## 7. Relation to the upgrade plan

- This plan delivers the upgrade plan's former **Phase 1**: SQLite tests, the helpers fix, lazy options and the full test suite. The upgrade plan now lists this plan as a prerequisite.
- The tests written here (attribute-based, `test_*` names) run unchanged on PHPUnit 10–12. They become the safety net for every upgrade phase. During the Livewire 4 phase, only `Volt::test` → `Livewire::test` changes.
