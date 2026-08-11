# ASTRA — Association of Stevens Teaching and Research Assistants (campaign site)

A **multi-page** unionization campaign website modeled on academic-worker
organizing drives (e.g. Drexel DRAW, Student Researchers United-UAW). Plain
HTML/CSS/JS — no framework, no build step to *run* it. Every page is a normal
`.html` file that shares one `styles.css` and one `script.js`, so it hosts on any
static host (GitHub Pages, Netlify, Cloudflare Pages, …).

```
union-website/
├── index.html          ← Home (hero + "Explore" hub)
├── about.html          ← About Us
├── why-a-union.html    ← Why a Union (issues)
├── international.html   ← International Workers & Research Funding
├── voices.html         ← Testimonials
├── committee.html      ← Meet the Organizing Committee
├── faq.html            ← FAQ
├── sign-card.html      ← Online Union Card (sign form)
├── get-involved.html   ← Get Involved (email signup)
├── styles.css          ← design system (colors live in :root at the top)
├── script.js           ← mobile nav, scroll reveals, count-up, form handling
├── assets/leaders/     ← committee photos (see README.txt in there)
│
├── onepage.html        ← the original single-page version (kept as the source
│                          for the hosted Artifact + for regenerating pages)
├── build-pages.js      ← regenerates all the pages above FROM onepage.html
└── astra-standalone.html← one self-contained file (inlined font/CSS/JS)
```

## Editing

Each page is independent — **edit the `.html` file for that tab directly.** The
shared header/footer are duplicated into every page. If you change the nav or
footer and want it applied everywhere at once, edit `onepage.html` and re-run the
generator:

```
node build-pages.js     # rewrites index.html + every tab page from onepage.html
```

> Note: re-running `build-pages.js` overwrites the generated pages. If you've been
> editing the pages directly, make your change in `onepage.html` too (or just keep
> editing the pages directly and don't run the generator).

## Customize (search the code for `CUSTOMIZE`)

| What | Where |
|------|-------|
| Campaign name / acronym (`ASTRA`, `Association of Stevens Teaching and Research Assistants`) | find/replace across all `.html` (and `onepage.html`) |
| Brand colors | `styles.css` → `:root` tokens (`--red`, `--gold`, …) |
| Headcount / % stats | `index.html` + `onepage.html` → `.hero__stats` (`data-count`) |
| Real testimonials | `voices.html` (samples now) |
| Committee members + photos | `committee.html`; drop photos in `assets/leaders/` (see its README) |
| National union affiliation | footer (`[your national union]`) — in every page |
| Contact email + social links | footer |
| Email-list signup backend | `script.js` → `listForm` handler |

## ⚠️ The authorization-card form — read before going live
A union authorization card is **confidential and legally significant**. The form
on `sign-card.html` is a **front-end demo** — it does not send data anywhere. For
a real campaign, connect it to your union's **official, secure card platform**
(your national union's card system, an Action Network "sign the card" action, or a
vetted committee-controlled form). See the `cardForm` handler in `script.js`. The
form already nudges signers to use a **personal** email (not `@stevens.edu`).

## Run locally
```
cd union-website
python3 -m http.server 8000   # then visit http://localhost:8000
```

## Deploy (free hosting)

### Option A — Netlify Drop (fastest, no account needed to try)
1. Go to <https://app.netlify.com/drop>
2. Drag the **`union-website`** folder onto the page.
3. You get a live URL instantly. (Make a free account to keep it / rename it.)

### Option B — GitHub Pages (free, permanent)
1. Create a new repository on <https://github.com> (e.g. `sraw-site`).
2. Upload the **contents** of `union-website/` so `index.html` is at the repo
   root — either drag the files into the repo's web uploader, or with git:
   ```
   cd union-website
   git init
   git add -A
   git commit -m "ASTRA campaign site"
   git branch -M main
   git remote add origin https://github.com/<you>/sraw-site.git
   git push -u origin main
   ```
3. In the repo: **Settings → Pages → Build and deployment → Source: Deploy from a
   branch → Branch: `main` / `/ (root)` → Save.**
4. Wait ~1 minute; your site is live at `https://<you>.github.io/sraw-site/`.

Both options serve `index.html` as the home page and every other tab as its own
URL (e.g. `/faq.html`). The external Google Font loads fine on a normal host.

## Note
This is a template for a worker-led campaign. It is independent of, and not
endorsed by, any university. Workers have a federally protected right to organize.
