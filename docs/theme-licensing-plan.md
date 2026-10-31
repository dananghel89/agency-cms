# Plan: legal use of the Start Bootstrap themes and bundled assets

Status: **Draft – not started**
Created: 2026-10-31

> This plan is a technical compliance checklist based on the published license texts. It is not legal advice. If the site is used commercially for a client, have the final inventory (section 2) reviewed by a lawyer.

## 1. Order and decisions

Plan order in `docs/`:

1. [project-review-fix-plan.md](project-review-fix-plan.md)
2. **This plan**
3. [github-migration-plan.md](github-migration-plan.md): its history rewrite also removes the assets deleted here from every past commit
4. [laravel-13-upgrade-plan.md](laravel-13-upgrade-plan.md)
5. [finish-project-plan.md](finish-project-plan.md)

| Topic | Decision |
|---|---|
| Theme code (SCSS/JS/Blade markup derived from SB Admin 2 and Agency) | Keep. It is **MIT**, and the only condition is to include the copyright and permission notice. Restore it (Phase 2) |
| Theme photos (no stated license) | **Replace with licensed stock** (Unsplash/Pexels), recording each file's source in a credits section |
| "Start Bootstrap" name and logo | **Rename to a neutral name:** **"Agency CMS"** (`agency-cms`), confirmed 2026-10-31. Add a credit line ("based on Start Bootstrap themes, MIT") in the README/notices |
| `public/demo/admin`, `public/demo/agency` | **Delete** from the repo now, and from the history during the GitHub migration |
| Trademarked client logos (Microsoft, Google, Facebook, IBM) | **Remove.** Using them suggests these companies are clients |
| unDraw illustrations | **Remove.** The unDraw license forbids redistributing them "in packs or otherwise", which a public repo does. The one used (the admin avatar) becomes a Font Awesome icon |
| Google Fonts and `placehold.co` | **Self-host.** Loading them from third-party servers sends visitors' IP addresses to those servers; LG München I, 20.01.2022, 3 O 17493/20 held this unlawful without consent |
| Third-party libraries | Keep. All are permissive. Add the missing license files and a `THIRD_PARTY_NOTICES.md` |
| Project's own license | **MIT** (decided 2026-10-31), compatible with everything bundled |

## 2. Inventory (checked 2026-10-31)

### 2.1 Themes

| Item | License | Where it is used | Problem | Action |
|---|---|---|---|---|
| **SB Admin 2 v4.1.3**, © 2013-2021 Start Bootstrap LLC | MIT | `resources/admin/scss/**`, `resources/admin/js/scripts.js`, admin/guest layouts and markup | The derived files have **no copyright notice**, and nothing in the built `admin-*.css`/`*.js` either | Phase 2 |
| **Agency v7.0.12**, © 2013-2023 Start Bootstrap LLC | MIT | `resources/agency/scss/**`, `resources/agency/js/scripts.js`, the public layout, components and home markup | The same: notices removed | Phase 2 |
| Original theme downloads | MIT (code) + unclear assets | `public/demo/admin`, `public/demo/agency`, served publicly at `/demo/…` | LICENSE files missing; they contain the unclear photos, unDraw files and logos | Phase 1: delete |

### 2.2 Images and graphics

| File(s) | Source | Problem | Action |
|---|---|---|---|
| `resources/agency/img/header-bg.jpg` | Agency theme | No stated license (stock photo) | Phase 5: replace |
| `resources/agency/img/portfolio/1-6.jpg` | Agency theme | No stated license | Phase 5: replace |
| `resources/agency/img/about/1-4.jpg` | Agency theme | No stated license | Phase 5: replace |
| `resources/agency/img/team/1-3.jpg` | Agency theme | No stated license; **identifiable people** presented as "our team" | Phase 5: replace (see the portrait rule there) |
| `resources/admin/img/{login,register,password}-image.jpg` | Unknown (600×800, edited in Photoshop, no metadata) | No known source | Phase 5: replace |
| `resources/agency/img/map-image.png` | Agency theme | No stated license | Phase 5: remove (the contact section keeps its dark background) |
| `resources/agency/img/navbar-logo.svg` | Agency theme | The theme's default logo (looks like the Start Bootstrap wordmark), a brand you don't own | Phase 4: replace with a text logo |
| `resources/agency/img/logos/{microsoft,google,facebook,ibm}.svg` | Agency theme | **Third-party trademarks**, shown as "clients" | Phase 3: remove |
| `resources/agency/img/close-icon.svg` | Agency theme | Fine under MIT, but unnecessary | Phase 5: replace with the Font Awesome `fa-times` icon |
| `resources/admin/img/undraw_*.svg` (7 files) | unDraw | Redistribution ban; only `undraw_profile.svg` is used | Phase 3: remove; avatar → `fa-user-circle` |
| `https://placehold.co/…` (4 places in `pages/home.blade.php`) | External service | Visitor IPs are sent to a third party | Phase 6: local placeholder SVGs |
| `public/favicon.png` | **Not yours** (confirmed 2026-10-31) | Unknown origin | **Done 2026-10-31:** replaced by the in-house favicon (yellow `#ffc800` square, white "A"): `public/favicon.ico` (16/32/48 px), `favicon.svg`, `apple-touch-icon.png`, linked in all three layouts; `favicon.png` deleted |

### 2.3 Fonts

| Font | License | Problem | Action |
|---|---|---|---|
| Nunito (admin, guest) | SIL OFL 1.1 | Loaded from `fonts.googleapis.com` / `fonts.gstatic.com` | Phase 6: self-host |
| Montserrat, Roboto Slab (public site) | SIL OFL 1.1 | Same | Phase 6: self-host |
| Font Awesome Free 5.15.3 (`public/vendor/fontawesome`) | Icons CC BY 4.0, fonts OFL 1.1, code MIT | Already self-hosted. The CSS banner gives attribution, but `LICENSE.txt` is missing | Phase 3: add LICENSE.txt |

### 2.4 Libraries (committed in `public/vendor`, or bundled by Vite)

| Library | License | Notice in the served file? | Action |
|---|---|---|---|
| Bootstrap 4.6.0 / 5.2.3 | MIT | Yes | Add LICENSE |
| jQuery 3.6.0 | MIT | Yes | Add LICENSE. Keep `jquery.slim*`: its tag is commented out in the public layout (review-plan R25) |
| jQuery Easing 1.4.1 | **BSD-3-Clause** (the notice is required in redistributions) | **No** (`.min.js`) | Add LICENSE + a banner; drop the unused `jquery.easing.compatibility.js` |
| DataTables 1.13 (+ Bootstrap 4 integration) | MIT | `.min.css` **no** | Add LICENSE |
| Chart.js 2.9.4 | MIT | Yes | **Unused** by the app (only by the demo), so delete `public/vendor/chart.js` and the npm dependency |
| Composer runtime packages (not committed; `vendor/` is ignored) | 68 MIT, 7 BSD-3, Apache-2.0 (`phpoption`); `nette/*` dual BSD-3/GPL | – | Listed in the notices; use nette under BSD-3-Clause |
| npm packages bundled by Vite | MIT (axios, Bootstrap SCSS…) | The Bootstrap banner survives in the built CSS | Listed in the notices |

## 3. Phases

### Phase 0 – Decisions

1. The review plan is finished on `master`. Work directly on `master`: commit and push each step only after you confirm it (I show the files and message first), with no feature branch.
2. Decisions (confirmed 2026-10-31): project name **Agency CMS** (`agency-cms`); license **MIT** (`Copyright (c) <year> Dan Anghel`); `public/favicon.png` was **not** your own and is already replaced (see section 2).
3. The local folder `start-bootstrap`, the WAMP vhost `start-bootstrap.test` and the DB name `start_bootstrap` **stay as they are**: these are internal, not public branding. Claude's project memory is also tied to the folder path. Renaming them is optional and separate.

### Phase 1 – Remove the theme downloads

1. `git rm -r public/demo`
2. Check that nothing references `/demo/` (`grep -rn "demo/" resources routes config`).
3. The originals stay available upstream: github.com/StartBootstrap/startbootstrap-sb-admin-2 and github.com/StartBootstrap/startbootstrap-agency. Link them in `THIRD_PARTY_NOTICES.md`.
4. Purging them from **past** commits happens in the GitHub migration (its history rewrite was updated for this).

### Phase 2 – Restore the theme copyright notices

1. Add the full license texts, verbatim from upstream:
   - `LICENSES/startbootstrap-sb-admin-2.txt` (MIT, "Copyright (c) 2013-2021 Start Bootstrap LLC")
   - `LICENSES/startbootstrap-agency.txt` (MIT, "Copyright (c) 2013-2023 Start Bootstrap LLC")
2. Add a preserved banner (`/*! … */`) at the top of each derived entry file:
   - `resources/admin/scss/admin.scss` and `resources/admin/js/scripts.js`:
     ```
     /*!
      * Based on Start Bootstrap - SB Admin 2 v4.1.3 (https://startbootstrap.com/theme/sb-admin-2)
      * Copyright 2013-2021 Start Bootstrap LLC
      * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin-2/blob/master/LICENSE)
      * Modifications copyright <year> Dan Anghel
      */
     ```
   - `resources/agency/scss/app.scss` and `resources/agency/js/scripts.js`: the same, with Agency v7.0.12 and 2013-2023.
3. `npm run build`, then check that both banners appear in the built `public/build/assets/*.css` and `*.js`. If the JS minifier drops them, keep legal comments in `vite.config.js` (e.g. `esbuild: { legalComments: 'inline' }`) and check again.

### Phase 3 – Library notices and asset removals

1. Add a license file to each vendor folder, copied from the matching `node_modules` package. Each must exist and be served along with the folder:
   - `public/vendor/bootstrap-4/LICENSE`, `bootstrap-5/LICENSE`, `jquery/LICENSE.txt`, `datatables/LICENSE`, `fontawesome/LICENSE.txt`, `jquery.easing/LICENSE`
2. `jquery.easing.min.js`: add the banner `/*! jQuery Easing v1.4.1 | (c) 2008 George McGinley Smith | BSD-3-Clause | https://github.com/gdsmith/jquery-easing */`.
3. **Delete unused files:**
   - `public/vendor/chart.js/` and the `chart.js` npm dependency
   - `jquery.easing.compatibility.js`

   Keep `public/vendor/jquery/jquery.slim*`: its `<script>` tag in `layouts/app.blade.php` is commented out (review-plan R25), not removed, so it can be switched back on.
4. **Trademarked logos:** delete `resources/agency/img/logos/*.svg`. Hide the home **Clients** section until the finish plan's Clients feature exists. That plan seeds neutral, self-made placeholder logos, never real brands.
5. **unDraw:** delete `resources/admin/img/undraw_*.svg`. The topbar avatar becomes `<i class="fas fa-user-circle fa-2x text-gray-400"></i>`.

### Phase 4 – Rebrand (neutral name)

Replace every visible "Start Bootstrap" / "SB Admin" name with **Agency CMS**:

| Where | Change |
|---|---|
| `.env.example`, your local `.env` | `APP_NAME="Agency CMS"` |
| `config/project.php` | `admin_title` → `'Agency CMS Admin'`; `options.site_title` default → `'Agency CMS'` |
| Local DB | The `options.site_title` row was seeded from `APP_NAME`. Update it in Admin → Options (or reseed) |
| `resources/views/components/navbar.blade.php` | Replace `<img navbar-logo.svg>` with a **text logo**: `{{ option('site_title') }}` in the brand style (font and colour via SCSS). It's editable and needs no image. Then delete `navbar-logo.svg` |
| `composer.json` | `"name": "dananghel89/agency-cms"`, real `description`, `"license": "MIT"` |
| `package.json` | Add `"name": "agency-cms"` and `"license": "MIT"` |
| `README.md` | Project name, plus a "Credits" section: "Admin and public themes based on [SB Admin 2](https://startbootstrap.com/theme/sb-admin-2) and [Agency](https://startbootstrap.com/theme/agency) by Start Bootstrap, MIT. This project is not affiliated with Start Bootstrap LLC." |
| GitHub repo name | `dananghel89/agency-cms`, already updated in the migration plan |

Side effect: a new `APP_NAME` changes the default cache prefix and session cookie name, so you'll be logged out once. This is expected.

### Phase 5 – Replace the photos with licensed stock

1. **Sources:** only **Unsplash** (Unsplash License) or **Pexels** (Pexels License). Both allow free commercial use with no attribution required. Both forbid selling unaltered copies and building a competing photo service, which doesn't apply here. Download each image from its page and record the page URL, author and date.
2. **New file names.** The GitHub migration purges the **old paths** from history, so a replacement must not reuse an old path:

   | Slot | Old file | New file (example) | Size |
   |---|---|---|---|
   | Masthead background | `img/header-bg.jpg` | `img/masthead.jpg` | 1900×1250, ≤ 400 KB |
   | Portfolio ×6 | `img/portfolio/1-6.jpg` | `img/portfolio/project-1…6.jpg` | 600×450 |
   | Timeline ×4 | `img/about/1-4.jpg` | `img/timeline/timeline-1…4.jpg` | 400×400 |
   | Team ×3 | `img/team/1-3.jpg` | `img/team/member-1…3.jpg` | 500×500 |
   | Admin login / register / password | `admin/img/*-image.jpg` | `admin/img/auth-login.jpg`, `auth-register.jpg`, `auth-password.jpg` | 600×800 |

3. **Portrait rule (team):** a stock photo of a real person shown as "our team" can misrepresent that person, and stock licenses don't include model releases for that use. Use only photos where **no face is identifiable** (from behind, silhouettes, hands at work); decided 2026-10-31. Real team photos come later from the site owner.
4. Update the SCSS/Blade references (`_masthead.scss`, `admin/_variables.scss`/`_login.scss`, the finish plan's seeders). Remove `map-image.png` and its `background-image` in `_contact.scss`. Replace `close-icon.svg` with `<i class="fas fa-times fa-3x"></i>` in the portfolio modal.
5. Delete all old files. Then check: `git ls-files resources | grep -E "header-bg|/portfolio/[0-9]|/about/|/team/[0-9]|-image\.(jpg|png)|navbar-logo|close-icon|logos/|undraw"` → nothing.

### Phase 6 – No third-party requests (fonts and placeholders)

1. **Fonts via Fontsource** (npm packages with the OFL-licensed font files, bundled by Vite into `public/build`):
   - `npm install @fontsource/nunito @fontsource/montserrat @fontsource/roboto-slab`
   - Import only the weights in use. Admin: Nunito 200/400/600/800 (+ italics, if used). Public site: Montserrat 400/700, Roboto Slab 100/300/400/700. Import in `resources/admin/scss/admin.scss` and `resources/agency/scss/app.scss`, or in the JS entries.
   - Remove the 3× `preconnect` + Google Fonts `<link>` tags from `layouts/admin`, `layouts/guest` and `layouts/app`.
2. **Placeholders:** add `resources/agency/img/placeholder.svg` (made in-house: a neutral grey box). Replace the 4 `https://placehold.co/…` URLs with `Vite::asset('resources/agency/img/placeholder.svg')`. The finish plan's `image_url` fallback uses the same file.
3. **Check:** load the home page, login and admin in the browser with DevTools → Network. There must be **no** request to any host other than `start-bootstrap.test`. `grep -rn "https\?://" resources/views` should only find links, never `src`/`href` loading assets.

### Phase 7 – Notices file and project license

1. `THIRD_PARTY_NOTICES.md` at the repo root, with these sections:
   - **Themes:** SB Admin 2, Agency (version, copyright, MIT, upstream URL, what was adapted). Full texts in `LICENSES/`.
   - **Front-end libraries:** Bootstrap 4/5, jQuery, jQuery Easing (full BSD-3 text), DataTables, Font Awesome Free (CC BY 4.0 / OFL / MIT).
   - **Fonts:** Nunito, Montserrat, Roboto Slab (SIL OFL 1.1, with the full text in `LICENSES/OFL-1.1.txt`).
   - **Images:** a table of each file, source URL, author, license (Unsplash/Pexels) and download date. Also list the in-house SVGs (placeholder, client placeholders) and the favicon.
   - **Server-side dependencies:** a summary from `php81 composer.phar licenses --no-dev` (MIT/BSD-3/Apache-2.0; nette used under BSD-3-Clause), and from `npx license-checker --production --summary` for npm.
2. `LICENSE` (project): MIT, "Copyright (c) <year> Dan Anghel".
3. `README.md` links to `LICENSE` and `THIRD_PARTY_NOTICES.md`.

### Phase 8 – Verification

1. `php81 artisan test` → green (update the tests that asserted the old texts, names or image paths).
2. `npm run build` → OK. The built CSS and JS contain the Start Bootstrap banners.
3. Text search (excluding `LICENSES/`, `THIRD_PARTY_NOTICES.md`, `README.md`, `docs/`):
   - `grep -rn "Start Bootstrap\|SB Admin" resources config .env.example` → no **visible** branding left. Only the `/*! … */` banners remain, plus the credits.
   - `grep -rn "fonts.googleapis\|fonts.gstatic\|placehold.co\|undraw" resources` → nothing.
4. Browser:
   - The public site, login and admin look right with the new photos, text logo and self-hosted fonts.
   - The Network tab shows no third-party hosts.
   - `/demo/admin/index.html` → 404.
5. Final review of the plan's commits on `master`, with your approval. Commit messages are one-line `type: description` (e.g. `chore: remove bundled theme demos`, `docs: add third-party notices and project license`).

## 4. Changes to the other plans (already applied)

| Plan | Change |
|---|---|
| GitHub migration | Runs after this plan. The repo name is the confirmed project name (`agency-cms`). The history rewrite now also **deletes** from every past commit: `public/demo/`, the old photo paths, `navbar-logo.svg`, `logos/`, `undraw_*`, `map-image.png`, `close-icon.svg`, `public/vendor/chart.js/`, `public/favicon.png`. New verification step: those paths don't exist in any commit |
| Finish project | Seeders use the **new** licensed images (Phase 5 file names); Clients are seeded with in-house placeholder logos (never real brands); `HasImage` falls back to the local `placeholder.svg` |
| Review / upgrade | Only the order (this plan sits between the review plan and the GitHub migration) |

## 5. Sources

- Start Bootstrap licenses (MIT): [Agency LICENSE](https://github.com/StartBootstrap/startbootstrap-agency/blob/master/LICENSE), [SB Admin 2 LICENSE](https://github.com/StartBootstrap/startbootstrap-sb-admin-2/blob/master/LICENSE)
- unDraw license: https://undraw.co/license
- Font Awesome Free license: https://fontawesome.com/license/free
- LG München I, 20.01.2022, 3 O 17493/20 (Google Fonts): [gesetze-bayern.de](https://www.gesetze-bayern.de/Content/Document/Y-300-Z-GRURRS-B-2022-N-612?hl=true)
- Unsplash License: https://unsplash.com/license · Pexels License: https://www.pexels.com/license/
