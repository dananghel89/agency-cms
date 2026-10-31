# Plan: move the repository from Bitbucket to GitHub

Status: **Draft – not started**
Created: 2026-10-31
Model: the bis-admin migration (2026-10-16 → 2026-10-22), done the same way: rewrite the history to the GitHub noreply address with one-line messages, push to GitHub, review it as a visitor, make it public, then delete Bitbucket.

## 1. Order and decisions

Plan order in `docs/`:

1. [project-review-fix-plan.md](project-review-fix-plan.md)
2. [theme-licensing-plan.md](theme-licensing-plan.md)
3. **This plan**
4. [laravel-13-upgrade-plan.md](laravel-13-upgrade-plan.md)
5. [finish-project-plan.md](finish-project-plan.md)

| Topic | Decision |
|---|---|
| When | **After** the review/fix plan **and** the theme-licensing plan are merged into `master`, **before** the Laravel 13 upgrade |
| From | `https://bitbucket.org/dan-anghel/start-bootstrap.git` (current `origin`, branch `master`) |
| To | `https://github.com/dananghel89/agency-cms.git` (HTTPS, like bis-admin). Project name **Agency CMS** confirmed 2026-10-31. Neither the GitHub name nor the public branding may be "start-bootstrap" |
| Visibility | **Public**, but only after you review it as a visitor. It is created **private** first |
| Commit identity | Every commit (author **and** committer): `Dan Anghel <7040327+dananghel89@users.noreply.github.com>`. Your gmail is never used (your per-host email rule) |
| Commit messages | One-line `type: description`. The original 3 commits are renamed (section 3); commits from the review plan are already written in this style |
| History scrub | Every past commit: `password` → `password`, `admin@example.com` → `admin@example.com`, `editor@example.com` → `editor@example.com`. The `example.com` author meta tag in the layouts is a public attribution and **stays** |
| History purge | Remove from **every** past commit the files that the licensing plan deleted: `public/demo/`, the old theme photos, the Start Bootstrap logo, the trademarked client logos, the unDraw files, `map-image.png`, `close-icon.svg`, `public/vendor/chart.js/`, the old `public/favicon.png` (not your artwork). A public history must not contain them either |
| Bitbucket | **Deleted** after GitHub is verified (you do this in the browser). Until then it is the fallback |
| Tools | Standard git only (`git filter-branch`, `git bundle`). `gh` and `git filter-repo` are not installed and are not needed. GitHub steps happen in the browser |

### What the history contains today (checked 2026-10-31)

- 4 commits on `master`, **all** authored and committed as `Dan Anghel <old-email@example.com>`. The review plan adds more commits with `dev@dananghel.com` (the local identity while the remote is Bitbucket).
- No `.env` was ever committed, and there are no API keys or tokens. The `secret`/`token` hits are framework config and env references only.
- Personal data in file contents:
  - `admin@example.com`, `editor@example.com` (`app/Models/Options.php`, `config/sb.php`)
  - the demo password `password` (`database/seeders/UserSeeder.php`)
  - both also appear in `CLAUDE.md` and in `docs/`, which get committed during the review plan.
- Size: about 7.3 MiB packed, so it's fine for GitHub.
- `.gitattributes` has `* text=auto eol=lf` and your global `core.autocrlf=true`. The rewrite runs with `core.autocrlf=false`, so line endings aren't touched (see 4.3).

## 2. Prerequisites (changes to the review plan)

These are already written into the review plan, so the migration has as little to rewrite as possible:

1. **Commit messages** during the review plan are one-line `type: description` (`fix:`, `feat:`, `test:`, `refactor:`, `chore:`, `docs:`, `style:`).
2. **R26** moves the admin credentials and emails to `.env` with **neutral defaults**:
   - `SEED_ADMIN_EMAIL=admin@example.com`, `SEED_ADMIN_PASSWORD=password`
   - `config('sb.options.admin_email')` → `env('SB_ADMIN_EMAIL', 'admin@example.com')`
   - `Options::userEmails()` reads the same settings.

   Your real values go only into your local `.env`, which is never committed. After this, the current files contain no personal values; only the history does, and this plan cleans it.

## 3. New commit messages

| Original | New |
|---|---|
| `Initial commit` | `chore: initial commit` |
| `Upload project files` | `feat: add Start Bootstrap agency site and SB Admin CMS` |
| `Update favicon` | `chore: update favicon` |
| `chore: update environment configuration, package lockfile, and add local composer binary` | unchanged (already in style) |
| Review-plan commits | unchanged (written in style) |

## 4. Steps

### 4.1 Pre-flight (local)

1. The review plan is merged into `master`. Run `git status` → clean. `git branch` → only `master`; delete any merged feature branches (`git branch -d …`).
2. `git fetch origin` and `git status` → `master` is in sync with `origin/master` (Bitbucket), so the fallback has everything.
3. `php81 artisan test` → green. This is the reference for after the rewrite.
4. **Backup** outside the repo: `git bundle create ../start-bootstrap-pre-github-YYYYMMDD.bundle --all`, then `git bundle verify ../start-bootstrap-pre-github-YYYYMMDD.bundle`.
5. Record the "before" state for comparison:
   - `git rev-list master > ../commits-before.txt`
   - `git rev-list --count master`

### 4.2 ⏸ You: create the empty GitHub repo (browser)

1. Go to github.com → **New repository**. Owner `dananghel89`, name **`agency-cms`**, **Private**.
   **Do not** add a README, .gitignore or license; it must be empty.
2. Settings → General → Features: turn off **Issues, Wiki, Projects, Discussions**, as in bis-admin.
3. Account settings → Emails: "Keep my email addresses private" stays **on**.
   - "Block command line pushes that expose my email" can stay **on** here, because every rewritten commit uses the noreply address.
   - In bis-admin it had to be turned off only because of `dev@` commits. If a push is rejected for email privacy, a commit still has a non-noreply address, so go back to 4.4.
4. Tell me when it's created.

### 4.3 Rewrite the history (local)

Run in Git Bash from the repo root. The command rewrites `master` only and keeps the original author/commit dates.

```bash
export FILTER_BRANCH_SQUELCH_WARNING=1
git -c core.autocrlf=false filter-branch --force \
  --env-filter '
    export GIT_AUTHOR_NAME="Dan Anghel"
    export GIT_AUTHOR_EMAIL="7040327+dananghel89@users.noreply.github.com"
    export GIT_COMMITTER_NAME="Dan Anghel"
    export GIT_COMMITTER_EMAIL="7040327+dananghel89@users.noreply.github.com"' \
  --msg-filter '
    sed -e "1s/^Initial commit$/chore: initial commit/" \
        -e "1s/^Upload project files$/feat: add Start Bootstrap agency site and SB Admin CMS/" \
        -e "1s/^Update favicon$/chore: update favicon/"' \
  --tree-filter '
    rm -rf public/demo public/vendor/chart.js public/favicon.png \n      resources/agency/img/header-bg.jpg resources/agency/img/map-image.png \n      resources/agency/img/navbar-logo.svg resources/agency/img/close-icon.svg \n      resources/agency/img/logos resources/agency/img/about \n      resources/agency/img/portfolio/[0-9].jpg resources/agency/img/team/[0-9].jpg \n      resources/admin/img/login-image.jpg resources/admin/img/register-image.jpg \n      resources/admin/img/password-image.jpg resources/admin/img/undraw_*.svg
    grep -rlIZ --exclude-dir=vendor --exclude-dir=node_modules \
      -e "password" -e "@example.com" . 2>/dev/null \
    | xargs -0 -r sed -i \
        -e "s/password/password/g" \
        -e "s/admin@example.com/admin@example.com/g" \
        -e "s/editor@example.com/editor@example.com/g"' \
  -- master
```

- `--tree-filter` checks out every commit (about 2 200 files), so expect a few minutes.
- The `rm -rf` paths are the **old** asset paths. The licensing plan gave every replacement a **new** file name, so the latest commit keeps its new images. (`[0-9].jpg` only matches the old `1.jpg`…`6.jpg` names, not `project-1.jpg` or `member-1.jpg`.) `resources/agency/img/about` is removed whole, because the replacements live in `img/timeline/`.
- The `Update favicon` commit only changed `public/favicon.png`, so after the purge it is empty. It is kept (no `--prune-empty`), so the commit count still matches in 4.4.
- `-I` skips binary files (e.g. `composer.phar`). The search is case-sensitive, so the `example.com` meta tag isn't touched.
- The original history stays reachable at `refs/original/refs/heads/master` until 4.7, as well as in the bundle and on Bitbucket.

### 4.4 Verify the rewrite (local, before any push)

All of these must pass; if one fails, reset to the backup (section 6) and fix the command.

1. **Same number of commits:** `git rev-list --count master` equals the "before" count.
2. **Identity:** `git log --format='%an <%ae> | %cn <%ce>' master | sort -u` → exactly one line, with the noreply address for both author and committer.
3. **Messages:** `git log --format=%s master` shows the new first-line messages and no other changes.
4. **Scrub is complete:** `git log -p master | grep -c -E 'password|@example.com'` → `0`, and `git grep -n -E 'password|@example.com' master` → nothing.
5. **Only the intended content changed.** For every old/new commit pair (`paste ../commits-before.txt <(git rev-list master)`), `git diff --stat <old> <new>` lists only:
   - files that contained the scrubbed values: `Options.php`, `config/sb.php`, `UserSeeder.php`, `CLAUDE.md`, `docs/*.md`
   - **deletions** of the purged paths

   Any other file means line endings changed; stop. The **newest** commit must show **no** difference at all (`git diff <old-head> master` → empty), because the licensing plan already removed those files there.
6. **Purged assets are gone:** `git log --name-only --format= master | grep -E "^public/demo/|header-bg|map-image|navbar-logo|close-icon|img/logos/|undraw_|/about/|portfolio/[0-9]\.jpg|team/[0-9]\.jpg|-image\.jpg|vendor/chart\.js|^public/favicon\.png$" | sort -u` → nothing.
7. **Dates kept:** `git log --format='%ad %s' master` shows the original dates (2024-07-18 …).
8. **App still works:** `php81 composer.phar dump-autoload`, then `php81 artisan test` → green.

### 4.5 Switch the remote and push

1. `git remote rename origin bitbucket`
2. `git remote add origin https://github.com/dananghel89/agency-cms.git`
3. `git config user.email` → must now print `7040327+dananghel89@users.noreply.github.com`. It comes from `~/.gitconfig` `includeIf "hasconfig:remote.*.url:https://github.com/**"` → `~/.gitconfig-github`.
4. `git push -u origin master`. This is a normal push into an empty repo, so no force is needed. Authenticate through the existing GitHub credential helper if prompted.
5. `git branch -vv` → `master` tracks `origin/master`.

### 4.6 ⏸ You: check on GitHub (still private)

- [ ] The commit count matches. Every commit shows your avatar, linked to `dananghel89`, so the noreply address is recognized.
- [ ] Open a few commits: the dates are original; the messages are as in section 3.
- [ ] Search the repo for `password` and `example.com` → only the `example.com` meta author tag.
- [ ] The repo has `LICENSE` and `THIRD_PARTY_NOTICES.md`, and there is no `public/demo`. No commit in the history shows the old theme photos or logos.
- [ ] The README (from review-plan R30) renders correctly.

### 4.7 Local cleanup

1. `git remote remove bitbucket` (the local link only; the Bitbucket repo still exists).
2. `git update-ref -d refs/original/refs/heads/master`, then delete the `.git-rewrite/` directory if it was left behind.
3. Purge the old objects locally: `git reflog expire --expire=now --all`, then `git gc --prune=now`.
4. Re-check: `git log --all --format='%ae' | sort -u` → only the noreply address.

### 4.8 ⏸ You: make it public

1. Check the repo **as a visitor** (logged out / incognito), as in bis-admin, with the extra checks from the bis-admin review:
   - [ ] the files, README and commit list look right
   - [ ] no personal data in the site content (`resources/views/**`): contact details, addresses, phone numbers
   - [ ] `.env.example` contains no real values
2. Settings → General → Danger Zone → **Change visibility → Public**.
3. Optional: add a repo description and topics (`laravel`, `livewire`, `bootstrap`, `cms`). The MIT `LICENSE` is already in the repo (licensing plan, Phase 7).

### 4.9 ⏸ You: delete the Bitbucket repo (browser)

**Only after 4.6 and 4.8 are done.** The Bitbucket repo still holds the original history with your gmail address and the unscrubbed values.

1. Bitbucket → `dan-anghel/start-bootstrap` → Repository settings → **Delete repository**.
2. Then delete the local bundle `../start-bootstrap-pre-github-*.bundle`; it also contains the old history. Optionally keep it until the Laravel 13 upgrade is merged, then delete it.

### 4.10 Docs and follow-up

1. In `CLAUDE.md`:
   - The repo is on GitHub, and commits use the noreply identity automatically.
   - The seeded login is `admin@example.com` / `password` unless `SEED_ADMIN_*` is set in `.env`.
2. The upgrade plan's Phase 0 branch is now created from the GitHub `master`. Its commits use the noreply address automatically; check with `git config user.email` before the first commit.
3. **Local DB:** your current local admin login is unchanged, because the database isn't touched. After the next `migrate:fresh --seed`, it becomes the `.env`/default values. Put your preferred `SEED_ADMIN_EMAIL`/`SEED_ADMIN_PASSWORD` and `SB_ADMIN_EMAIL` in your local `.env`.

## 5. Checklist (end state)

- [ ] `git remote -v` → only `origin = https://github.com/dananghel89/agency-cms.git`
- [ ] No past commit contains `public/demo/`, the old theme photos, the Start Bootstrap logo, the client logos or unDraw files
- [ ] All commits use the noreply address and one-line `type: description` messages
- [ ] `password` / `@example.com` appear nowhere in the history
- [ ] The GitHub repo is public, with Issues/Wiki/Projects/Discussions off
- [ ] The Bitbucket repo is deleted; the local bundle is deleted (or kept until after the upgrade)
- [ ] `php81 artisan test` green on the GitHub `master`

## 6. Rollback

| Situation | Action |
|---|---|
| Rewrite looks wrong (before push) | `git reset --hard refs/original/refs/heads/master`, or restore from the bundle: `git fetch ../start-bootstrap-pre-github-*.bundle master:master --force` (after `git checkout --detach`) |
| Push went wrong (repo still private) | Delete and recreate the GitHub repo in the browser, then push again. **No force-push without asking** |
| Anything else before 4.9 | Bitbucket is untouched and stays the source of truth until it is deleted |
