# Third-party notices

Agency CMS is released under the MIT License (see [LICENSE](LICENSE)). It includes or is based on the third-party work listed below. Full license texts are in the [LICENSES](LICENSES/) folder or next to each library in `public/vendor/`.

## Themes

| Theme | Version | Copyright | License | Used for |
|---|---|---|---|---|
| [SB Admin 2](https://github.com/StartBootstrap/startbootstrap-sb-admin-2) | 4.1.3 | © 2013-2021 Start Bootstrap LLC | MIT ([text](LICENSES/startbootstrap-sb-admin-2.txt)) | Admin and login layouts, `resources/admin/scss`, `resources/admin/js/scripts.js` |
| [Agency](https://github.com/StartBootstrap/startbootstrap-agency) | 7.0.12 | © 2013-2023 Start Bootstrap LLC | MIT ([text](LICENSES/startbootstrap-agency.txt)) | Public site layout and sections, `resources/agency/scss`, `resources/agency/js/scripts.js` |

Both themes were adapted into Blade/Livewire views, and their SCSS and JS were modified. The original theme images, logos and illustrations are **not** included; they were replaced as listed under Images. This project is not affiliated with Start Bootstrap LLC.

## Front-end libraries (`public/vendor/`)

| Library | Version | Copyright | License |
|---|---|---|---|
| [Bootstrap](https://getbootstrap.com/) | 4.6.0 (admin), 5.2.3 (public site) | © 2011-2022 The Bootstrap Authors | MIT (`bootstrap-4/LICENSE`, `bootstrap-5/LICENSE`) |
| [jQuery](https://jquery.com/) | 3.6.0 | © OpenJS Foundation and other contributors | MIT (`jquery/LICENSE.txt`) |
| [jQuery Easing](https://github.com/gdsmith/jquery-easing) | 1.4.1 | © 2008 George McGinley Smith | BSD-3-Clause (full text below) |
| [DataTables](https://datatables.net/) + Bootstrap 4 integration | 1.10.24 | © 2008-2021 SpryMedia Ltd | MIT (`datatables/LICENSE`) |
| [Font Awesome Free](https://fontawesome.com/) | 5.15.3 | © Fonticons, Inc. | Icons CC BY 4.0, fonts SIL OFL 1.1, code MIT (`fontawesome/LICENSE.txt`) |

### jQuery Easing license (BSD-3-Clause)

```
TERMS OF USE - jQuery Easing

Open source under the BSD License.

Copyright © 2008 George McGinley Smith
All rights reserved.

Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of
conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list
of conditions and the following disclaimer in the documentation and/or other materials
provided with the distribution.

Neither the name of the author nor the names of contributors may be used to endorse
or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
OF THE POSSIBILITY OF SUCH DAMAGE.
```

## Fonts

Self-hosted through the [Fontsource](https://fontsource.org/) npm packages and bundled by Vite into `public/build`. No font is loaded from a third-party server.

| Font | Copyright | License |
|---|---|---|
| Nunito (admin) | © 2014 The Nunito Project Authors | SIL Open Font License 1.1 ([text](LICENSES/font-nunito.txt)) |
| Montserrat (public site headings) | © 2011 The Montserrat Project Authors | SIL Open Font License 1.1 ([text](LICENSES/font-montserrat.txt)) |
| Roboto Slab (public site body) | © 2018 The Roboto Slab Project Authors | Apache License 2.0 ([text](LICENSES/font-roboto-slab.txt)) |

## Images

### Photos

All photos are from [Unsplash](https://unsplash.com/) under the [Unsplash License](https://unsplash.com/license), downloaded on 2026-10-31 and cropped to size. Credit is not required by the license but is given here.

| File | Photo | Photographer |
|---|---|---|
| `resources/agency/img/masthead.jpg` | https://unsplash.com/photos/9l_326FISzk | Luca Bravo |
| `resources/agency/img/portfolio/project-1.jpg` | https://unsplash.com/photos/8dgFq8Vbelo | Andy Brown |
| `resources/agency/img/portfolio/project-2.jpg` | https://unsplash.com/photos/FeuEg-8XlA8 | Balázs Kétyi |
| `resources/agency/img/portfolio/project-3.jpg` | https://unsplash.com/photos/eWuUwZWuWvE | Immo Wegmann |
| `resources/agency/img/portfolio/project-4.jpg` | https://unsplash.com/photos/l8cvrt3Hpec | Daniel Schludi |
| `resources/agency/img/portfolio/project-5.jpg` | https://unsplash.com/photos/AXngd0B1ppc | 0x3d |
| `resources/agency/img/portfolio/project-6.jpg` | https://unsplash.com/photos/BK8g9zWjETI | 0x3d |
| `resources/agency/img/timeline/timeline-1.jpg` | https://unsplash.com/photos/35Ff2Poa53s | Declan Sun |
| `resources/agency/img/timeline/timeline-2.jpg` | https://unsplash.com/photos/ASDnnQ74qMs | Zan Lazarevic |
| `resources/agency/img/timeline/timeline-3.jpg` | https://unsplash.com/photos/yapBRdPWxik | Faizur Rehman |
| `resources/agency/img/timeline/timeline-4.jpg` | https://unsplash.com/photos/Yzqq5YssljQ | Marina Reizberg |
| `resources/agency/img/team/member-1.jpg` | https://unsplash.com/photos/Hcfwew744z4 | Christin Hume |
| `resources/agency/img/team/member-2.jpg` | https://unsplash.com/photos/vZJdYl5JVXY | Kaitlyn Baker |
| `resources/agency/img/team/member-3.jpg` | https://unsplash.com/photos/_UeY8aTI6d0 | Sergey Zolkin |
| `resources/admin/img/auth-login.jpg` | https://unsplash.com/photos/s-5usW1C-r0 | Miguel Joya |
| `resources/admin/img/auth-register.jpg` | https://unsplash.com/photos/ItvVUpgac0o | Simone Hutsch |
| `resources/admin/img/auth-password.jpg` | https://unsplash.com/photos/0B7ijYKaKcE | Alesia Kazantceva |

The team photos are demo content and show no identifiable people.

### Made for this project

These are covered by the project's MIT License.

| File | Notes |
|---|---|
| `resources/agency/img/world-map.png` | Dotted world map, drawn from [Natural Earth](https://www.naturalearthdata.com/) 110m land data (public domain) |
| `resources/agency/img/clients/client-1…4.svg` | Placeholder client logos (fictional, no real brands) |
| `resources/agency/img/placeholder.svg`, `placeholder-square.svg` | Image placeholders |
| `public/favicon.ico`, `favicon.svg`, `apple-touch-icon.png` | Favicon ("A" on #ffc800) |

## Server-side and build dependencies

Installed by Composer and npm, not committed to this repository. Summary of their licenses:

- **Composer** (runtime, `composer licenses --no-dev`): 68 packages under MIT; 5 under BSD-3-Clause (`league/commonmark`, `league/config`, `nikic/php-parser`, `tijsverkoyen/css-to-inline-styles`, `vlucas/phpdotenv`); `phpoption/phpoption` under Apache-2.0; `nette/schema` and `nette/utils`, dual-licensed BSD-3-Clause or GPL-2.0/3.0, used under **BSD-3-Clause**.
- **npm**: Bootstrap, jQuery, DataTables, autoprefixer, postcss, Vite, laravel-vite-plugin, Sass and axios under MIT; jQuery Easing under BSD-3-Clause; Font Awesome Free under CC BY 4.0 / OFL 1.1 / MIT; the Fontsource packages under OFL 1.1 (Nunito, Montserrat) and Apache-2.0 (Roboto Slab).
