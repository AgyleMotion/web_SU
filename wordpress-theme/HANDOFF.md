# ASTRA-UAW WordPress theme: handoff notes

Built to the brief in `WORDPRESS-CONVERSION.md`. This covers what was delivered, where it differs from the brief, how it was tested, and what is still open.

## Deliverables

| File | What it is |
|---|---|
| `astra-uaw.zip` | Installable theme: Appearance → Themes → Add New → Upload Theme |
| `astra-uaw/` | Theme source (same as the zip) |
| `EDITING-GUIDE.md` | Plain-language guide for the committee |
| `HANDOFF.md` | This file |
| `blueprint.json` | Optional: spins up a local test site with WordPress Playground (see Testing) |

## Install

1. Upload `astra-uaw.zip` and activate it.
2. Go to **Appearance → ASTRA starter content** and click **Load starter content**. This creates the 7 pages, 8 testimonials (with photos), 10 departments, 8 FAQ items, 6 issue cards and the 3 menus, sets Home as the static front page, and turns on `/%postname%/` permalinks if they were off. It never overwrites existing content and is safe to re-run.

## ⚠️ Open before launch

1. **Union card platform URL (brief §7.2).** The card form on Sign your card is a front-end placeholder and stores or sends nothing. Paste UAW's official card platform link into **Customize → ASTRA Campaign → Card signing**. The placeholder is then replaced by a button to that platform. Until then, the placeholder's "Thank you, your card has been recorded" message is misleading, so launching without the URL is not recommended.
2. **Email signup ("Keep me posted").** Also a placeholder. Nothing is collected. It needs a mailing list provider (Action Network, Mailchimp or similar), which is not in the brief.
3. **Social links.** Instagram and Bluesky point to `#`. Set real URLs in **Customize → ASTRA Campaign → Footer**, or clear a label to hide that link.
4. **Brief §11 questions**, still unanswered: multisite, and the final domain. The theme is multisite-safe in principle but was only tested on a single site. Redirects and links use `home_url()`, so a subdirectory install works.
5. **FAQ copy.** "How can I do more than sign?" still says "Join the organizing committee… via the Get involved section", but Get involved no longer has a committee option. The committee has asked to leave it as is for now.

## Where this differs from the brief, and why

The brief was written before the latest round of site changes. The theme reproduces the **current live site**, which is the stated reference:

- **Header:** logo only. The "ASTRA" wordmark is hidden when the logo loads, as on the live site. It is still output (from the Site Title) as the no-logo fallback.
- **Menu:** Home · About · Why we're organizing · Testimonials · International students · FAQ · Meet Lincoln · Sign your card, matching the live site, not the brief's older labels.
- **Testimonials:** 8, not 3. They support a **featured image** for the headshot (not in the brief), shown in the same 96px circle, with the initials fallback. A 192×192 `astra-avatar` image size keeps them sharp on retina screens.
- **Departments:** the organizers section is hidden on the live site, so departments import as **drafts**, and the section renders only when at least one department is published.
- **Content migration:** the brief says to enter content by hand. Instead, a one-click importer (`inc/starter-content.php`) loads it, with the text generated from the static HTML (`inc/starter-data.php`).
- **Templates:** `page-{slug}.php` files apply automatically by slug, so no template needs assigning.
- **More Customizer settings than listed.** To honor "no hardcoded content", every home page heading, the About boxes, comparison lists, Explore cards, the red CTA band, the marquee phrases and the card platform link are also editable. All settings have sanitize callbacks.
- **Page-level fields:** a "Section heading" meta box on pages (small label, heading, intro), plus template-specific fields: the organizers heading and note on Testimonials, numbered steps on Sign your card, and the signup box text on Get involved.
- **Two footer menu locations** (Campaign, Get involved). The menu name is used as the column heading. The contact email and the Follow column come from the Customizer.
- **Logo:** the importer does **not** set a Site Logo. The bundled logo files are pre-rendered at exactly 2× display size for the header (420px) and footer (540px), so they stay sharp. A logo set in Site Identity overrides both.
- **CSS:** `styles.css` is included unchanged. A clearly marked "WordPress compatibility" block is appended (additions only): admin bar offset, `.screen-reader-text`, paragraph margins inside quote, issue and FAQ text, group-block wrappers inside the International grid, and basic styles for generic new pages.
- **Text and emoji output:** `wptexturize` (curly quotes) and the emoji-to-image script are disabled, so copy and emoji render exactly as on the static site.
- **Lincoln heart counter:** moved into `js/script.js`, guarded to run only when `#heartBtn` and `#heartCount` exist. Endpoints, namespace (`astra-uaw-lincoln-x7k2`), key (`hearts`) and the `lincoln-hearts` localStorage fallback are unchanged.
- **Nav underline on home sections** (About and Why we're organizing highlight while scrolled into view): rewritten to work from WordPress menu URLs instead of the static `index.html` links.

## How it was tested

WordPress (latest) on **PHP 8.1** in WordPress Playground, with `WP_DEBUG` and `WP_DEBUG_DISPLAY` on:

- **Pages and errors:** every page returns 200 with no PHP notices, warnings or deprecations. The 404 page works.
- **Legacy redirects:** all 9 legacy `.html` URLs return a 301 to the right place (§7.3).
- **Pixel check:** at 1440px, every section and element on all 7 pages measures identically to `agylemotion.github.io/web_SU` (compared element by element in the browser).
- **Mobile:** at 375px, the hamburger opens and closes, `aria-expanded` updates, the current tab gets `aria-current`, and nothing scrolls sideways.
- **Heart counter:** reads the shared Abacus total (same number as the live site).
- **Editability:** each item was done in the admin and confirmed on the site. Added a testimonial (initials fallback shown), added an FAQ item, published a department (the organizers section appeared), renamed and reordered a nav item, changed a stat and its label in the Customizer, created a brand-new page (styled, with header, footer and CTA band), edited a custom field through the block editor's Save button, swapped the logo in Site Identity (header and footer), and set the card platform URL (placeholder replaced by the button).
- **Block validity:** all 49 imported blocks validate in the block editor, so there are no "unexpected content" warnings.

Not tested: a real host, multisite, PHP 8.2 to 8.4 on a live server (Playground ran 8.1), or a formal accessibility audit.

To reproduce locally (needs Node):

```
npx @wp-playground/cli@latest server --port=9400 --php=8.1 --workers=1 \
  --mount=$PWD/astra-uaw:/wordpress/wp-content/themes/astra-uaw \
  --blueprint=$PWD/blueprint.json --login
```

Use `--workers=1`. With Playground's default of 6 workers, simultaneous writes to its test database can collide. This is a Playground quirk, not a theme issue.

## Code map

| File | Purpose |
|---|---|
| `functions.php` | Setup, enqueues, legacy redirects, favicon fallback, meta description, `js` class, title separator, texturize and emoji off |
| `inc/post-types.php` | The 4 custom post types, admin order column, `astra_uaw_query()` |
| `inc/meta-boxes.php` | Custom fields (nonce-checked, sanitized per type) |
| `inc/customizer.php` | The ASTRA Campaign panel. All settings are defined once, in `astra_uaw_customizer_sections()` |
| `inc/nav-walker.php` | Flat `<a>` menu output, `nav__link`/`nav__cta`, `aria-current` |
| `inc/template-tags.php` | Shared helpers: links, logo, section heads, brand mark |
| `inc/starter-content.php`, `inc/starter-data.php` | One-click importer and its data |
| `front-page.php`, `page-*.php`, `page.php`, `index.php`, `404.php` | Templates |
| `template-parts/cta-band.php` | The red CTA band |
| `js/script.js` | Original script + heart counter + WordPress-aware nav underline |
