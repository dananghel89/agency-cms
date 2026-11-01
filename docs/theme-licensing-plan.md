# Plan: using the Start Bootstrap themes

Status: **Done** – revised 2026-11-01 (see section 5)
Created: 2026-10-31

> This is a technical checklist based on the published license texts, not legal advice.

## 1. Order

1. [project-review-fix-plan.md](project-review-fix-plan.md)
2. **This plan**
3. [github-migration-plan.md](github-migration-plan.md)
4. [laravel-13-upgrade-plan.md](laravel-13-upgrade-plan.md)
5. [finish-project-plan.md](finish-project-plan.md)

## 2. Facts (checked 2026-11-01)

| Source | What it says |
|---|---|
| [startbootstrap.com/licenses](https://startbootstrap.com/licenses) (General Info) | "The information in this section applies to the paid, pro products sold on Start Bootstrap. Our free themes, templates, and snippets are licensed under the MIT License." |
| [startbootstrap.com/licenses/freelancer](https://startbootstrap.com/licenses/freelancer) | A license tier for the **paid** Pro themes (single domain, no redistribution…). It does **not** apply to SB Admin 2 or Agency |
| SB Admin 2 download (`README.md`, `LICENSE`) | "All of the free templates and themes on Start Bootstrap are released under the MIT license, which means you can use them for any purpose, even for commercial projects." MIT, © 2013-2021 Start Bootstrap LLC |
| Agency download (`css/styles.css` banner) | Agency v7.0.12, © 2013-2023 Start Bootstrap, "Licensed under MIT" |
| MIT License | Use, copy, modify, publish and distribute freely. The only condition is to keep the copyright and permission notice |

**How this project is used:** a personal demo at `demo.dananghel.com` (not a client or commercial site), with its source code public on GitHub, crediting Start Bootstrap in the README.

## 3. Required vs optional

| Item | Required by MIT? | Decision |
|---|---|---|
| Keep the copyright and license notice | **Yes** | Done: `/*! … */` banners in the theme SCSS/JS with the copyright and a link to the MIT license (they survive the build), the same form Start Bootstrap uses in its own files |
| Credit Start Bootstrap in the README | No (nice to have) | Done: README "Credits" section |
| Keep the theme's demo images, logos and unDraw illustrations | No restriction | **Kept as in the theme** (decided 2026-11-01) |
| `public/demo` (the static theme demos) | No | **Removed** 2026-11-01: not used by the app; the originals are on Start Bootstrap's site and GitHub. Also purged from history in the GitHub migration |
| Library notices in `public/vendor/*` | Yes, and already met: each library carries its copyright and license in its own files (jQuery Easing in the unminified `jquery.easing.js`) | Vendor files kept identical to the theme. The LICENSE files and the `jquery.easing.min.js` banner added on 2026-10-31 were **removed** on 2026-11-01 |
| Project license | Your choice | Done: MIT (`LICENSE`, `composer.json`, `package.json`) |
| `LICENSES/` folder and `THIRD_PARTY_NOTICES.md` | No | Added 2026-10-31, **removed** 2026-11-01 at your request: the theme banners, the notices inside the vendor files and the README credit cover what is needed |
| Name "Agency CMS" | No | Your choice (2026-10-31). Navbar logo: `resources/agency/img/agency-cms-logo.svg`, "Agency CMS" drawn as outlines in Kaushan Script (SIL OFL 1.1, the font of the theme's own logo), same colour and height. The theme's `navbar-logo.svg` stays in the repo unchanged |
| Self-hosted fonts and image placeholders | No (privacy best practice: no visitor IPs sent to Google Fonts / `placehold.co`) | Done: Fontsource packages; local `placeholder.svg` and `placeholder-square.svg` |
| New favicon | – | Done; the old one wasn't yours |

## 4. Final state

| Phase | Commit | Result |
|---|---|---|
| Theme notices | f430cef | Banners in `admin.scss`, `app.scss` and both `scripts.js` |
| Name | b76f2e3 | `APP_NAME`, `config/project.php` titles, `composer.json` / `package.json` names, README credit |
| Fonts and placeholders | 3d86968 | Fontsource (Nunito, Montserrat, Roboto Slab), local placeholders, `.square` timeline image |
| Project license | e6e17bd | `LICENSE` (MIT) |
| Revision | this commit | Original theme assets restored; `LICENSES/`, `THIRD_PARTY_NOTICES.md`, the vendor LICENSE files and `public/demo` removed (section 5) |

Note: Roboto Slab is Apache-2.0 (not OFL); Nunito and Montserrat are OFL 1.1. The committed DataTables is 1.10.24.

## 5. Revision 2026-11-01

The first version of this plan was written without reading Start Bootstrap's license pages or the theme downloads, and without asking how the site is used. It treated the project like a commercial client site and removed or replaced assets that MIT does not require removing:

- Deleted `public/demo/` (the original static themes)
- Replaced the Agency photos (`header-bg.jpg`, `portfolio/1-6.jpg`, `about/1-4.jpg`, `team/1-3.jpg`) with Unsplash photos
- Replaced `map-image.png` with a generated world map, and `close-icon.svg` with a Font Awesome icon
- Replaced the client logos with placeholder logos, and `navbar-logo.svg` with a text logo
- Removed the unDraw illustrations (topbar avatar became an icon)
- Replaced the login/register/password photos with Unsplash photos
- Removed `public/vendor/chart.js`, `jquery.easing.compatibility.js` and the `chart.js` npm package

All of these were **restored** to their original files on 2026-11-01, and the replacements were deleted. `public/demo` was restored too, then removed again at your request (not used by the app). The GitHub migration plan purges only `public/demo` and the old `public/favicon.png` from history. The finish plan seeds the original theme images and logos.

## 6. Sources

- Start Bootstrap licenses: https://startbootstrap.com/licenses, https://startbootstrap.com/licenses/freelancer
- SB Admin 2: https://startbootstrap.com/theme/sb-admin-2 · [LICENSE](https://github.com/StartBootstrap/startbootstrap-sb-admin-2/blob/master/LICENSE)
- Agency: https://startbootstrap.com/theme/agency · [LICENSE](https://github.com/StartBootstrap/startbootstrap-agency/blob/master/LICENSE)
