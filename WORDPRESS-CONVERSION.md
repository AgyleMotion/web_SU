# ASTRA-UAW: Static site → WordPress theme conversion brief

**For the developer (or AI agent) doing the conversion. This document is self-contained: you do not need any prior context.**

---

## 0. Goal in one sentence

Convert an existing, finished static HTML/CSS/JS campaign website into a **custom classic WordPress theme**, keeping the visual design **pixel-identical**, while making the content editable by non-technical people through **standard WordPress admin screens**.

**The design is done and approved. Do not redesign anything.** Your job is plumbing, not styling.

---

## 1. Source material

| Item | Where |
|---|---|
| Source code | `https://github.com/AgyleMotion/web_SU` |
| Live reference site (what it must look like) | `https://agylemotion.github.io/web_SU/` |
| Zip of the static files | Provided alongside this document |

### Source file inventory
```
index.html            Home
who-we-are.html       Testimonials + organizers by department
international.html    International students
faq.html              FAQ (accordion)
sign-card.html        Union authorization card + form
get-involved.html     Get involved + email signup
meet-lincoln.html     Campaign dog page (has an interactive heart counter)
about.html            Legacy redirect -> index.html#about
committee.html        Legacy redirect -> who-we-are.html#committee
styles.css            ALL styling (single file, ~548 lines)
script.js             Nav toggle, scroll reveals, stat count-up, form handlers
assets/               logo.png, favicon.png, lincoln.jpg
```

> **Note:** the generated `.html` pages are produced from `onepage.html` by `build-pages.js`. **Ignore that build system entirely.** Convert the generated `.html` pages. After conversion, WordPress is the source of truth and the Node build script is retired.

---

## 2. Target and constraints

**Build:** a standalone **classic WordPress theme** (PHP templates). Not a block/FSE theme, not a child theme.

**Hard constraints:**
- ✅ **No paid plugins.** No ACF Pro, no Toolset. Use WordPress core APIs only.
- ✅ **No page builder dependency.** No Elementor, Divi, or WPBakery.
- ✅ Target **WordPress 6.x** and **PHP 8.1+**. No deprecated APIs, no PHP notices/warnings.
- ✅ **Design must not change.** Reuse `styles.css` as-is.
- ✅ Must be installable as a zip via **Appearance → Themes → Add New → Upload Theme**.

> If the hosting team says they standardize on Elementor or a specific parent theme, **stop and ask** before proceeding. This brief assumes a standalone custom theme.

---

## 3. THE POINT OF THIS PROJECT: what must be editable

This is the acceptance bar. A non-technical committee member must be able to do **all** of the following with **zero code**:

| What | Where they do it | Mechanism you build |
|---|---|---|
| Nav menu: add/remove/rename/reorder tabs | Appearance → Menus | `register_nav_menus()` |
| Logo | Appearance → Customize → Site Identity | `add_theme_support('custom-logo')` |
| Any image | Media Library | Core |
| Create a brand-new page that matches the site | Pages → Add New | `page.php` generic template |
| Body text on existing pages | Block editor on that page | `the_content()` |
| **Testimonials** (add/edit/delete a quote) | "Testimonials" admin menu | Custom post type |
| **Departments + organizer names** | "Departments" admin menu | Custom post type |
| **FAQ** questions/answers | "FAQ" admin menu | Custom post type |
| **Issue cards** ("What we're working toward") | "Issues" admin menu | Custom post type |
| Hero headline, sub-text, and the 3 stat numbers | Appearance → Customize | Customizer settings |
| Footer contact email, social links, disclaimer | Appearance → Customize | Customizer settings |

**Anti-requirement:** do **not** hardcode this content into PHP templates. Hardcoded content = failed conversion.

---

## 4. Theme structure to create

Theme slug: `astra-uaw`

```
astra-uaw/
├── style.css               Theme header comment + the ENTIRE existing styles.css
├── index.php               Required fallback
├── functions.php           Setup, enqueues, menus, CPTs, meta boxes, customizer
├── header.php              Existing site header + wp_head()
├── footer.php              Existing footer + wp_footer()
├── front-page.php          Home page
├── page.php                Generic template for NEW pages (must inherit design)
├── page-who-we-are.php     Testimonials loop + departments loop
├── page-faq.php            FAQ loop
├── page-international.php  International students
├── page-sign-card.php      Card page + form
├── page-get-involved.php   Get involved + signup
├── page-meet-lincoln.php   Lincoln + heart counter
├── 404.php
├── screenshot.png          1200x900 preview for the themes screen
├── inc/
│   ├── post-types.php      CPT registration
│   ├── meta-boxes.php      Custom fields for CPTs
│   ├── customizer.php      Customizer settings
│   └── nav-walker.php      Menu classes (see §6.3)
├── js/
│   └── script.js           Existing script.js + the heart-counter logic
└── assets/
    ├── logo.png
    ├── favicon.png
    └── lincoln.jpg
```

---

## 5. Custom post types (spec)

Register in `inc/post-types.php`. All should be `'public' => true`, `'show_in_rest' => true` (so the block editor works), with sensible `menu_icon` and `supports`.

### 5.1 `astra_voice` — Testimonials
- Admin label: **Testimonials**
- `supports`: `title`, `editor`
- **Title** = person's name (e.g. "Sara Bender-Bier")
- **Content** = the quote text
- **Meta field** `_astra_department` (text) = e.g. "Biomedical Engineering"
- **Meta field** `_astra_initials` (text, optional) = e.g. "SB". **If empty, auto-derive** from the title's first letters in the template.
- Order by `menu_order`, then date.

### 5.2 `astra_dept` — Departments
- Admin label: **Departments**
- `supports`: `title`, `page-attributes` (for ordering)
- **Title** = department name (e.g. "Mechanical Engineering")
- **Meta field** `_astra_organizers` (text) = comma-separated names (e.g. "Nima Kalantari, Jett Langhorn, Mohamed Eraky")
- Order by `menu_order`.

### 5.3 `astra_faq` — FAQ
- Admin label: **FAQ**
- `supports`: `title`, `editor`, `page-attributes`
- **Title** = the question
- **Content** = the answer (rendered inside the `<details>` body)
- Order by `menu_order`.

### 5.4 `astra_issue` — Issues
- Admin label: **Issues**
- `supports`: `title`, `editor`, `page-attributes`
- **Title** = card heading (e.g. "Fair, livable pay")
- **Content** = card description
- **Meta field** `_astra_icon` (text) = an emoji, e.g. `💵`
- Order by `menu_order`.

> Use simple `add_meta_box()` + `save_post` handlers in `inc/meta-boxes.php`. Sanitize on save (`sanitize_text_field`), escape on output (`esc_html`). Verify nonces.

---

## 6. Conversion steps

### 6.1 `style.css`
Prepend the WordPress theme header, then paste the **entire existing `styles.css` unchanged** below it:

```css
/*
Theme Name: ASTRA-UAW
Theme URI: https://github.com/AgyleMotion/web_SU
Author: ASTRA organizing committee
Description: Campaign site theme for ASTRA, the Association of Stevens Teaching and Research Assistants.
Version: 1.0.0
License: GPL-2.0-or-later
Text Domain: astra-uaw
*/

/* ---- everything below is the existing styles.css, unmodified ---- */
```

**Do not refactor, rename, or "clean up" the CSS.** The markup depends on these exact class names.

### 6.2 `functions.php`
Must include:
- `add_theme_support()` for: `title-tag`, `post-thumbnails`, `html5` (search-form, comment-form, gallery, caption, style, script), `custom-logo`, `automatic-feed-links`.
- `register_nav_menus()` with **two** locations: `primary` (main tabs) and optionally `footer_campaign` / `footer_involved`.
- Enqueue in a `wp_enqueue_scripts` hook:
  - Google Fonts: `Anton`, `Cinzel:wght@500;600;700`, `Inter:wght@400;500;600;700;800` (`&display=swap`).
  - The theme stylesheet (`get_stylesheet_uri()`), versioned with `filemtime()`.
  - `js/script.js` in the footer with `array()` deps and `true` for in-footer.
- `require_once` the four files in `inc/`.

### 6.3 Nav menu (IMPORTANT GOTCHA)
The existing CSS targets **specific classes** that WordPress does not output by default. The menu must render as:

```html
<div class="nav__links" id="nav-links">
  <a class="nav__link" href="...">Home</a>
  <a class="nav__link" aria-current="page" href="...">About</a>
  ...
  <a class="nav__cta" href="...">Sign your card</a>
</div>
```

Requirements:
1. Add `class="nav__link"` to every menu link via the `nav_menu_link_attributes` filter (or a custom walker in `inc/nav-walker.php`).
2. Add `aria-current="page"` to the link whose menu item has the `current-menu-item` class. **The active-tab underline styling depends on `[aria-current="page"]`, not on WordPress's own `current-menu-item` class.**
3. The "Sign your card" button uses `nav__cta` instead of `nav__link`. Implement by letting an admin add the CSS class `nav__cta` to that menu item (enable **Screen Options → CSS Classes** in the menu editor) and swapping the class in your walker when present.
4. Use `items_wrap` so the container is `<div class="nav__links" id="nav-links">` with **no `<ul>`/`<li>`**, or adjust with a walker. The mobile toggle JS in `script.js` targets `#nav-links` and toggles the class `is-open`. Keep that contract intact.

### 6.4 `header.php` / `footer.php`
Take the existing `<header class="site-header">` and `<footer class="footer">` markup verbatim and add the WordPress essentials:

- `<html <?php language_attributes(); ?>>`
- `<?php wp_head(); ?>` before `</head>`
- `<body <?php body_class(); ?>>`
- `<?php wp_footer(); ?>` before `</body>`
- Keep the skip link: `<a class="skip-link" href="#main">Skip to content</a>`
- Brand link `href` → `<?php echo esc_url( home_url( '/' ) ); ?>`
- Logo: output `the_custom_logo()` if set, **otherwise** fall back to `get_template_directory_uri() . '/assets/logo.png'`. Preserve the existing `onload`/`onerror` behaviour and the `.brand__mark` / `.has-logo` class logic, which swaps in the image and hides the inline SVG fallback.
- Footer year: replace the hardcoded year with `<?php echo esc_html( date_i18n( 'Y' ) ); ?>`.

### 6.5 Page templates
For each template, copy the corresponding section markup from the static HTML, then replace the repeating blocks with `WP_Query` loops over the CPTs from §5, and replace prose with `the_content()` where it is page-level copy.

- **`front-page.php`** — hero (Customizer values) + marquee + about (uses `the_content()` of the page set as front page) + issues (loop `astra_issue`) + comparison + explore cards + CTA band.
- **`page-who-we-are.php`** — loop `astra_voice` into `.voices__grid`, then loop `astra_dept` into `.dept-grid`. **Keep the section IDs `#voices` and `#committee`**, legacy links point at them.
- **`page-faq.php`** — loop `astra_faq` into `.faq__list`, each as `<details class="faq__item"><summary>TITLE</summary><div class="faq__a">CONTENT</div></details>`.
- **`page-meet-lincoln.php`** — see §7.1.
- **`page.php`** — generic. Must render header, footer, container, and `the_content()` inside the site's standard section wrapper so **new pages automatically inherit the design**. This is what makes "Pages → Add New" work.

### 6.6 Customizer (`inc/customizer.php`)
Add a panel **"ASTRA Campaign"** with sections and settings. Use `sanitize_callback` on every setting.

**Hero:**
- `astra_hero_eyebrow` (text) — default: `Association of Stevens Teaching and Research Assistants`
- `astra_hero_title` (textarea, allows limited HTML: `<br>`, `<span class="u-mark">`) — default: `We love what we do.<br />We'd like a <span class="u-mark">say</span> in how it's done.`
- `astra_hero_lede` (textarea)
- `astra_stat1_num` (`441`), `astra_stat1_label` (`PhD students reached`)
- `astra_stat2_num` (`10`), `astra_stat2_label` (`departments covered`)
- `astra_stat3_num` (`1`), `astra_stat3_label` (`united voice at the table`)

> The stat count-up animation in `script.js` reads the `data-count` attribute. Output `data-count="<?php echo esc_attr( $num ); ?>"` and keep the inner text as `0`.

**Footer:**
- `astra_contact_email`, `astra_affiliation` (default `UAW`), `astra_social_1..3` (label + URL pairs), `astra_disclaimer` (textarea).

---

## 7. Things that must not break

### 7.1 The Lincoln heart counter
`meet-lincoln.html` has a heart button with a **shared global counter**. In the static site this JS is **inline inside the section**. Move it into `js/script.js`, guarded so it no-ops on other pages.

- Required element IDs: `#heartBtn` (button) and `#heartCount` (the number).
- It calls an external service. Keep these endpoints **exactly**:
  - Read: `GET https://abacus.jasoncameron.dev/get/astra-uaw-lincoln-x7k2/hearts`
  - Increment: `GET https://abacus.jasoncameron.dev/hit/astra-uaw-lincoln-x7k2/hearts`
  - Response shape: `{"value": <number>}`
- **Do not change the namespace or key** (`astra-uaw-lincoln-x7k2` / `hearts`) or the existing count resets to zero.
- Keep the `localStorage` fallback (key `lincoln-hearts`) for when the service is unreachable.
- Keep the pop animation (`is-pop` class) and floating heart (`.heart__float`).
- Lincoln's photo should come from the page's **featured image** if set, falling back to `assets/lincoln.jpg`.

### 7.2 ⚠️ The union card form — LEGAL, READ THIS
`sign-card.html` contains a form (`#cardForm`) that is a **non-functional front-end placeholder**.

- Union authorization cards are **legally significant and confidential**.
- **Do not** wire this form to WordPress, do not store submissions in the database, and do not email them.
- It must be pointed at **UAW's official, secure card platform** (URL to be supplied by the campaign).
- Until that URL exists, leave it as a visual placeholder or replace the submit with a link to the official platform.
- Flag this explicitly in your handoff notes.

### 7.3 Legacy URL redirects
The old site had `.html` URLs. Preserve them so existing links and printed materials keep working. Add redirects (via `template_redirect` in `functions.php`, or server config):

| Old | New |
|---|---|
| `/index.html` | `/` |
| `/who-we-are.html` | `/who-we-are/` |
| `/international.html` | `/international/` |
| `/faq.html` | `/faq/` |
| `/sign-card.html` | `/sign-card/` |
| `/get-involved.html` | `/get-involved/` |
| `/meet-lincoln.html` | `/meet-lincoln/` |
| `/about.html` | `/#about` |
| `/committee.html` | `/who-we-are/#committee` |

Use **301** redirects. Keep the page slugs exactly as listed above.

### 7.4 Other
- **Fonts:** if the host blocks outbound requests to Google Fonts, the site degrades to system fonts gracefully. Self-hosting the fonts is an acceptable optional improvement.
- **Accessibility:** preserve the skip link, all `aria-label`s, `aria-expanded` on the nav toggle, and `aria-current` on the active tab.
- **Scroll reveals:** `script.js` adds `.reveal` animations via IntersectionObserver and the `js` class on `<html>`. Keep the inline `<script>document.documentElement.classList.add('js');</script>` in `header.php` so no-JS users still see content.

---

## 8. Content migration

After the theme works, load the real content. All current copy lives in the source HTML files.

1. Create pages with these exact slugs: `who-we-are`, `international`, `faq`, `sign-card`, `get-involved`, `meet-lincoln`. Create a `home` page and set it as the static front page (Settings → Reading).
2. Assign the matching page template to each.
3. Build the **primary menu** in this order: Home · About · Why a union · Who are we · International students · FAQ · Meet Lincoln, plus a "Sign your card" item with the `nav__cta` CSS class.
   - "About" and "Why a union" are **anchor links to the front page**: `/#about` and `/#issues` (add them as Custom Links).
4. Enter the **3 testimonials**, **10 departments**, **8 FAQ items**, and **6 issue cards** from the source HTML into the matching CPTs.
5. Upload `logo.png` and set it as the Site Logo; set `lincoln.jpg` as the Meet Lincoln page's featured image.

---

## 9. Acceptance criteria (definition of done)

**Visual**
- [ ] Every page is visually identical to `https://agylemotion.github.io/web_SU/` at desktop, tablet, and mobile widths.
- [ ] The header lockup (logo + "ASTRA" wordmark) matches, including the Cinzel font and letter-spacing.
- [ ] Mobile hamburger menu opens and closes.
- [ ] Scroll-reveal animations and the stat count-up still fire.

**Editability (test each one in the admin)**
- [ ] Renaming and reordering a nav item changes the site.
- [ ] Adding a new Department appears on the Who-are-we page.
- [ ] Adding a new Testimonial appears on the Who-are-we page.
- [ ] Adding an FAQ item appears in the accordion.
- [ ] Changing a stat number in the Customizer updates the hero.
- [ ] **Creating a brand-new page via Pages → Add New produces a correctly styled page** with working header, footer, and nav.
- [ ] Swapping the logo via Customize → Site Identity works.

**Technical**
- [ ] No PHP warnings/notices with `WP_DEBUG` on, under PHP 8.1+.
- [ ] No paid plugins required. Theme activates cleanly on a fresh WP install.
- [ ] All output escaped (`esc_html`, `esc_url`, `esc_attr`, `wp_kses_post`).
- [ ] All meta-box saves use nonces and sanitize input.
- [ ] Legacy `.html` URLs 301-redirect correctly.
- [ ] Lincoln heart counter reads and increments the shared count.
- [ ] Theme installs from a zip via Appearance → Themes → Add New.

---

## 10. Deliverables

1. `astra-uaw.zip` — the installable theme.
2. A short **`EDITING-GUIDE.md` for the committee** (non-technical), covering: how to add a department, add a testimonial, add an FAQ, edit the stats, change the menu, and add a new page. Plain language, no jargon.
3. Handoff notes listing anything left open, **including the card-form platform URL (§7.2), which must be resolved before launch.**

---

## 11. Questions to resolve before starting

Ask the hosting/data team:
1. Do they use a **page builder** (Elementor/Divi) or a mandated parent theme? If yes, this brief's approach changes.
2. Is it **WordPress multisite**?
3. What is the **official UAW card platform URL** for §7.2?
4. What is the final **domain**?

---

*Design and content by the ASTRA organizing committee. Keep the tone of all copy calm and welcoming, not militant, and keep sentence case in headings. Avoid em dashes in any copy you touch.*
