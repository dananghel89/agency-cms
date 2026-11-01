# Plan: finish the project (unfinished features)

Status: **Draft – not started**
Created: 2026-10-31

## 1. Order and prerequisites

This is the **fifth** plan. Start it only when all earlier plans are merged into `master`:

1. [project-review-fix-plan.md](project-review-fix-plan.md): bug fixes, SQLite test suite, "not implemented yet" placeholders.
2. [theme-licensing-plan.md](theme-licensing-plan.md): MIT notices and credits, rebranding, self-hosted fonts. The original theme images are kept.
3. [github-migration-plan.md](github-migration-plan.md): repo moved to GitHub (public), history rewritten.
4. [laravel-13-upgrade-plan.md](laravel-13-upgrade-plan.md): Laravel 13, PHP 8.4, slim structure, Livewire 4 (Volt removed), Vite 8.
5. **This plan:** builds the missing features directly on the upgraded stack.

Prerequisite check (Phase 0) before any work:
- `php artisan --version` → Laravel 13.x
- `composer show livewire/livewire` → 4.x, and `livewire/volt` is not installed
- `php artisan test` → green
- WAMP Apache is on PHP 8.4 (upgrade-plan Checkpoint B done)

## 2. Decisions

| Topic | Decision |
|---|---|
| Stack | Laravel 13 · Livewire 4 single-file components (`use Livewire\Component`) · PHP 8.4 (`php`, global `composer`) · Vite 8 · PHPUnit 12 |
| Roles | **Single admin role.** Every user who can log in is a full admin (as today). No roles, permissions screen or activity log |
| Page content | **Markdown textarea** with live preview, rendered with Laravel's built-in `Str::markdown()` with raw HTML stripped. No JS editor, no HTML sanitizer package |
| Images | Upload to the `public` disk (`storage/app/public/…`, served through the existing `public/storage` junction). **Validation only, no resizing**: jpg/png/webp up to 2 MB, with minimum dimensions per section |
| Home page texts | **Editable in the admin** (Options). Client logos become a small **Clients** section in the admin |
| Translations | **Single language.** Strings overrides the texts of `__()` in the site language. No language switcher |
| Delete | Works from the list (a `DELETE` route behind a JS confirm, compatible with DataTables) and from the edit screen (Livewire action, like Strings). Both use the same model-level cleanup (e.g. deleting the image file) |
| Migrations | New features get **new migrations** (`add_image_to_…`, `create_clients_table`); existing migrations are not edited |
| Way of working | Test first for every feature (`Livewire::test`, `Storage::fake('public')`, `UploadedFile::fake()->image()`), then the code, then a green suite. One commit per phase (with your approval) |

### Traceability (review-plan backlog → this plan)

| Backlog item (review plan, section 6) | Phase |
|---|---|
| Create/edit screens for Pages, Services, Portfolio, Timeline, Team, Users | 3, 4, 8 |
| Delete actions on lists | 1 |
| Images for Portfolio, Team, Timeline | 2 |
| Pages front end (templates, SEO description, editor) | 4 |
| Translations used by `__()` | 9 |
| Users and roles | 8 (single role; permissions and logs **not chosen**) |
| Contact spam workflow, phone format | 7 |
| `@import` → `@use` in SCSS | 10 |
| *New:* hard-coded home texts and client logos | 5, 6 |
| *New:* navbar anchors break on inner pages; footer Privacy/Terms links go nowhere | 4 |
| *New:* off-brand framework error pages | 10 |

---

## 3. Phases

### Phase 0 – Prerequisites

1. Run the prerequisite check (section 1).
2. Work directly on `master`: commit and push each phase only after you confirm it (I show the files and message first), with no feature branch.
3. Back up the database (`mysqldump start_bootstrap > ../start_bootstrap-backup-YYYYMMDD.sql`).

### Phase 1 – Shared admin infrastructure

Shared pieces first, so phases 3–8 contain only section-specific code.

1. **Publish status:** a trait `App\Models\Concerns\HasPublishStatus` with `scopePublished()` (`status = 'publish'`) and `statuses()` (`['publish' => __('Published'), 'draft' => __('Draft')]`). It replaces the per-model scopes added in review-plan R06 and is used by Service, Portfolio, Timeline, Team, Page and Client.
2. **Images:** a trait `App\Models\Concerns\HasImage` that provides:
   - an `image_url` accessor: `Storage::disk('public')->url($this->image)`, or `null`
   - deletion of the old file when `image` changes (`updating`) and when the model is deleted (`deleting`)
3. **Delete from lists:**
   - Add `destroy` to every `Route::resource(...)->only([...])` in `routes/admin.php`.
   - Each controller's `destroy()` runs `findOrFail` → `delete()` → flashes `status` → redirects to the index.
   - `components/common/row-actions` renders `delete` as a small `<form method="POST">` with `@method('DELETE')` and `onsubmit="return confirm(...)"`.
   - The index views pass the route URL for `delete` instead of the old dummy names.
4. **Form components** (in `components/common/`):
   - `input-file`: file input, validation feedback, preview of the current image or of the new upload (`temporaryUrl()`)
   - `status-select`: uses `HasPublishStatus::statuses()`
   - `form-control type="select"` marks the selected option
5. **Lists:**
   - Add **Position** and **Status** columns (status as a badge) to every content list.
   - Sort DataTables by position by default (`data-order`), and exclude the actions column from sorting and search.
6. **Edit-screen pattern** (reference: `livewire/admin/string.blade.php`):
   - One Livewire component per section handles both create and edit (`$item` null = create).
   - `store()` redirects to edit with a flash message, `update()` dispatches `<section>-updated`, and `destroy()` redirects to the index with a flash message.
   - The create/edit Blade wrappers replace the shared "not implemented yet" view from the review plan, and the controllers pass `item` (`findOrFail`).
7. Tests:
   - `Admin/DeleteFromListTest`: data provider over every resource; the row is deleted, a guest can't delete, and an unknown id → 404.
   - `HasImageTest`: replacing or deleting an image removes the old file (`Storage::fake`).

### Phase 2 – Image columns and demo images

1. Migration `add_image_to_portfolio_team_timeline`: a nullable `string('image')` on `portfolio`, `team` and `timeline`. Add `image` to each model's `$fillable` and use `HasImage`.
2. Public views (`pages/home.blade.php`): replace `$item->image` with `$item->image_url`, falling back to the local `resources/agency/img/placeholder.svg` (from the licensing plan; no `placehold.co`).
3. Seeders copy the **original Agency theme images** into the public disk, so a fresh install has a complete demo:
   - `resources/agency/img/portfolio/1…6.jpg` → `portfolio/`
   - `team/1…3.jpg` → `team/`
   - `about/1…4.jpg` → `timeline/`
   - Seeder rows reference those files.
4. Check that `public/storage` (currently a Windows junction to `storage/app/public`) serves files. Otherwise run `php artisan storage:link`.
5. Tests: `HomePageTest` shows uploaded image URLs and falls back to placeholders.

### Phase 3 – Content sections: Services, Portfolio, Timeline, Team

Each section gets its form component, its create/edit wrappers and its tests (create, edit, validation errors, delete, image upload where applicable).

All four sections also have **`position`** (integer 0–255, matching the `unsignedTinyInteger` column, default 0) and **`status`** (`publish|draft`). Image rules are `nullable|image|mimes:jpg,jpeg,png,webp|max:2048` plus the minimum dimensions listed.

| Section | Fields and validation | Notes |
|---|---|---|
| **Services** | `title` required, max 255 · `content` nullable, max 1000 · `icon` required, `regex:/^[a-z0-9-]+$/` (a Font Awesome 5 solid icon name, e.g. `shopping-cart`) | Live icon preview next to the input, plus a link to the Font Awesome 5 icon list |
| **Portfolio** | `title` required · `subtitle` nullable · `content` nullable, max 2000 · `client` required · `category` required · `image` min 600×450 | The image is shown in the grid and in the modal; 4:3 ratio recommended |
| **Timeline** | `title` required (e.g. "2009-2011") · `subtitle` nullable · `content` nullable, max 1000 · `image` min 200×200 | Square image recommended (shown as a circle) |
| **Team** | `name` required · `profession` nullable · `social_links.*` nullable `url` (one input per `Options::socialNetworks()`, the same UI as Options) · `image` min 300×300 | `social_links` stays an array cast. The admin list renders the links in Blade with escaping (review-plan R12) |

The admin list columns are updated to show the new fields: a thumbnail for image sections, and the icon preview for Services.

### Phase 4 – Pages (CMS) and public page rendering

1. **Admin form** (`livewire/admin/page.blade.php`):
   - `title` required, max 255
   - `slug` required, `alpha_dash`, max 255, unique (ignoring the current page). It is generated from the title (`Str::slug`) until edited by hand, and some slugs are reserved: `admin`, `login`, `forgot-password`, `reset-password`, `livewire`, `up`, `storage`, `api`.
   - `description` nullable, max 300; used as the meta description
   - `content` Markdown, nullable, max 50 000
   - `template` must exist in `config('project.page_templates')`, initially `['default' => 'Default']`
   - `status`
   - The Markdown preview is a Livewire computed property. Show it in a side-by-side or tabbed layout.
2. **Rendering:** `Str::markdown($page->content, ['html_input' => 'strip', 'allow_unsafe_links' => false])`. Only this output is printed unescaped. Test that `<script>` and `javascript:` links are removed.
3. **Public layout for inner pages:**
   - `x-app-layout` gets a `masthead` flag (default `true`). Inner pages use a compact page header with the page title: the same background as the masthead, shorter, plus a small SCSS block in `resources/agency/scss/sections/`.
   - The navbar links become `route('home').'#services'` etc. when not on the home page. ScrollSpy only runs on the home page.
   - The navbar always has the `navbar-shrink` style on inner pages, so it stays readable on a light background.
   - `@section('description', $page->description)` sets the meta description.
4. **Footer links:** Options gets `privacy_page` and `terms_page` selects (published pages). The footer links to `route('page', $slug)` and hides a link when it isn't set.
5. **Seeder:** `PageSeeder` creates "Privacy Policy" (`privacy-policy`) and "Terms of Use" (`terms-of-use`) with placeholder Markdown, and sets both options.
6. Tests:
   - Page CRUD with slug uniqueness, reserved slugs and auto-generation.
   - Public page: a published page renders with its title, content and meta description; a draft page → 404.
   - Markdown XSS stripping.
   - Footer links appear when the options are set.
   - Navbar links on an inner page point to `/#…`.

### Phase 5 – Clients (logos section)

1. Migration `create_clients_table`: `name`, `url` nullable, `image`, `position` (unsigned tinyint), `status` (`publish|draft`), timestamps. Model `Client` with `HasImage` and `HasPublishStatus`.
2. Admin:
   - sidebar entry `['clients', 'Clients', 'handshake', 'interface', 'clients.index']` in `config/project.php`
   - resource routes and controller
   - list and form: `name` required · `url` nullable `url` · `image` **required on create**, `image|mimes:jpg,jpeg,png,webp|max:1024`
3. **SVG:** admin uploads are raster-only: jpg/png/webp (decided 2026-10-31). An uploaded SVG can carry scripts when opened directly from the same domain. `ClientSeeder` seeds the **Agency theme's demo logos** (`resources/agency/img/logos/*.svg`; trusted files, so SVG is fine). Real client logos are uploaded later by the site owner.
4. Home "Clients" section: loop over `Client::published()->orderBy('position')`, link only when `url` is set, and hide the section when there are no clients.
5. Dashboard counter for Clients.
6. Tests: CRUD, the raster-only upload rule, and the home section showing published clients.

### Phase 6 – Editable home page texts (Options)

1. New option keys with defaults in `config('project.options')`. The defaults equal today's hard-coded texts, so **nothing changes visually** until edited:

   | Key | Default (current text) |
   |---|---|
   | `site_description` | *(empty, used as the home meta description)* |
   | `masthead_subheading` | Welcome To Our Studio! |
   | `masthead_heading` | It's Nice To Meet You |
   | `masthead_button` | Tell Me More |
   | `services_subheading`, `portfolio_subheading`, `about_subheading`, `team_subheading`, `contact_subheading` | Lorem ipsum dolor sit amet consectetur. |
   | `about_closing` | Be Part / Of Our / Story! (line breaks, rendered with `nl2br(e())`) |
   | `team_text` | Lorem ipsum dolor sit amet, consectetur adipisicing elit… |
   | `privacy_page`, `terms_page` | *(empty; see Phase 4)* |

2. **Options screen:** split it into cards: *General* (site title, description, admin email), *Home page* (the texts above), *Social links*, *Legal pages*. Saving uses the `updateOrCreate` loop from review-plan R14, extended to the new keys. The input types are text and textarea.
3. **Views:**
   - `components/masthead`, `pages/home` and `livewire/pages/contact` read `option('…')` and drop the `__()` wrapper around content values.
   - Section headings ("Services", "Portfolio", …) stay as `__()` strings, so Strings can still override them.
4. Tests: defaults render the current texts; edited options appear on the home page; the HTML in `about_closing` is escaped.

### Phase 7 – Contacts: spam workflow and phone format

1. **Admin:**
   - The contact show page gets **Mark as spam / Not spam** and **Delete** (a Livewire component `livewire/admin/contact.blade.php`).
   - The list gets a status filter (All / Inbox / Spam), where Inbox means `inherit`, and a status badge.
2. **Honeypot:** add a hidden `website` field to the public contact form. If it is filled in, save the contact with `status = 'spam'`, send **no** email, and still show the normal success message. This works alongside the rate limit from review-plan R13.
3. **Phone format:** replace `numeric|digits:9` with `regex:/^\+?[0-9\s\-().]{7,20}$/`, which accepts international formats (decided 2026-10-31). Every example, placeholder, seeder and test uses the **fake Spanish number `+34 612 34 56 78`** (the standard example number for Spanish mobiles, not a real person's). The form placeholder becomes `Your Phone * (e.g. +34 612 34 56 78)`.
4. **Dashboard:** add a Contacts counter (inbox and spam) and a "Latest messages" card with the last 5 non-spam contacts.
5. Tests: honeypot submissions are stored as spam without mail; spam/not-spam toggles; delete; the phone formats; the dashboard counters.

### Phase 8 – Users (single admin role)

1. **Form** (`livewire/admin/user.blade.php`):
   - `name` required · `email` required, unique (ignoring the current user)
   - `password` required on create, optional on edit, with `Password::defaults()` and `confirmed`
   - New users get `email_verified_at = now()`
2. **Safety rules:**
   - You **can't delete yourself**, and the last remaining user **can't be deleted**. Both are enforced in `destroy()` (list and edit screen) and hidden in the UI.
   - Editing yourself links to the Profile page.
3. The list shows "(you)" next to the current user.
4. Tests: create, edit with and without a new password, unique email, both deletion guards.

### Phase 9 – Strings wired into `__()` (single language)

1. **Loader:** `App\Translation\DatabaseOverrideLoader` implements `Illuminate\Contracts\Translation\Loader` and decorates the file loader, so its `addNamespace`, `addJsonPath` and `namespaces` simply pass through.
   - For JSON lookups (`group = '*'`, `namespace = '*'`) it merges `Translation::pluck('replace', 'key')` over the file lines.
   - Results are cached with `Cache::rememberForever('project.translations')`.
   - A `QueryException` (fresh DB, table missing) returns `[]`, so artisan commands and the first migration never break.
2. **Registration:** in `AppServiceProvider::register()`:
   ```php
   $this->app->extend('translation.loader', fn ($loader) => new DatabaseOverrideLoader($loader));
   ```
3. **Cache invalidation:** the `Translation` model clears `project.translations` on `saved` and `deleted`.
4. **Admin hint:** the Strings form explains that **Key** must match the original text exactly (e.g. `Contact Us`, `Send Message`, including `:placeholders`). The Strings list shows where a key is used, if that's feasible; otherwise just the hint.
5. Tests: an override changes the output of `__('Contact Us')` on the home page; updating the string takes effect immediately (cache cleared); a missing table doesn't throw.

### Phase 10 – Polish and tech debt

1. **Error pages:**
   - `resources/views/errors/404.blade.php` uses the public theme (`x-app-layout` with a compact header).
   - `500.blade.php` and `503.blade.php` are **standalone**: no `option()`, no DB access, inline styles. They must still render when the database is down.
   - The admin uses the same 404 page.
2. **Cleanup:**
   - Remove the shared "not implemented yet" view, and any leftover stub components that no longer have a reference.
   - Remove the commented-out `permissions`/`logs` sidebar entries (not chosen; they can come back if roles are added later).
3. **SCSS:** migrate `@import` → `@use`/`@forward` in `resources/admin/scss` and `resources/agency/scss` where Bootstrap 4/5 allow it. Bootstrap 4.6 SCSS doesn't support `@use` in its own sources, so keep `@import` for the Bootstrap import itself and remove the matching `silenceDeprecations` entries only where they are no longer needed. Check that `npm run build` passes and the output looks the same.
4. **Accessibility:**
   - `alt` text on images: portfolio title, team member name, client name.
   - `aria-label` on icon-only action buttons.

### Phase 11 – Verification and documentation

1. `php artisan migrate:fresh --seed` (local; backup from Phase 0) → the demo site is complete, with the theme's demo images and client logos, and the legal pages.
2. `php artisan test` → green; `vendor/bin/pint --test` → clean; `npm run build` → OK.
3. Smoke checklist (section 4).
4. Docs:
   - **`CLAUDE.md`:** the admin CRUD pattern (shared traits, delete route + Livewire destroy, image handling), the Options keys, the translation loader, page templates, Clients.
   - **`README.md`:** the feature list.
   - Mark the review plan's backlog items as done (pointing to this plan).
5. Final review of the plan's commits on `master`, with your approval.

## 4. Smoke checklist (final)

Public site:
- [ ] Home: masthead texts and every section subheading come from Options; published items only, ordered by position; images (uploaded or seeded) or placeholders; clients logos with links
- [ ] Portfolio modals show the image and texts
- [ ] Footer: Privacy Policy / Terms of Use open their pages; social links from Options
- [ ] Inner page: compact header, navbar links return to home sections, meta description set; a draft or unknown page → themed 404
- [ ] Contact: validation, international phone, success; honeypot → stored as spam with no mail; rate limit

Admin:
- [ ] Services, Portfolio, Timeline, Team, Pages, Clients, Users: create → edit → delete from the edit screen; delete from the list (with confirm); validation messages; image upload, preview and replace (the old file is removed)
- [ ] Pages: slug auto-generation, reserved slug rejected, Markdown preview
- [ ] Contacts: filter, mark spam / not spam, delete; dashboard counters and latest messages
- [ ] Users: can't delete yourself or the last user
- [ ] Options: all cards save; changes appear on the site
- [ ] Strings: an override changes a site text immediately

CLI:
- [ ] `php artisan test`, `pint --test`, `npm run build`, `php artisan route:list` (destroy routes present)

## 5. Not included (decided)

| Item | Why |
|---|---|
| Roles, permissions screen, activity log | Single admin role chosen |
| Image resizing / thumbnails | Validation only chosen; can be added later with intervention/image |
| Multi-language site / language switcher | Single language chosen; Strings overrides texts only |
| WYSIWYG editor | Markdown chosen |
| Public registration, email verification | Stay disabled (admin-only users) |

## 6. Bugs and questions found while implementing

| ID | Location | Problem / question | Decision |
|---|---|---|---|
| – | – | – | – |
