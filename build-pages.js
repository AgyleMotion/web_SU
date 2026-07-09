/* Generate a multi-page static site from the single-page onepage.html.
   Each nav tab becomes its own .html file, sharing styles.css + script.js.
   Re-running regenerates every page FROM onepage.html (edits to onepage.html
   flow through; direct edits to the generated pages are overwritten). */
const fs = require("fs");

const DIR = "/Users/mohammadtawdik/Desktop/web_SU";
const src = fs.readFileSync(DIR + "/onepage.html", "utf8");

/* ---- pull reusable pieces out of the single page ---- */
const parsedHeader = src.match(/<header class="site-header"[\s\S]*?<\/header>/)[0];
const parsedFooter = src.match(/<footer class="footer">[\s\S]*?<\/footer>/)[0];
const heroSection  = src.match(/<section class="hero"[\s\S]*?<\/section>/)[0];
const marquee      = src.match(/<div class="marquee"[\s\S]*?<\/div>\s*<\/div>/)[0];
const grab = (id) => {
  const m = src.match(new RegExp('<section\\b[^>]*id="' + id + '"[\\s\\S]*?</section>'));
  if (!m) throw new Error("section not found: #" + id);
  return m[0];
};
const S = {
  about: grab("about"), issues: grab("issues"), international: grab("international"),
  voices: grab("voices"), committee: grab("committee"), faq: grab("faq"),
  card: grab("card"), involved: grab("involved"),
};

/* ---- rewrite in-page #anchors to real pages ---- */
const HASH = {
  "#about": "index.html#about", "#issues": "index.html#issues", "#international": "international.html",
  "#voices": "index.html#voices", "#committee": "committee.html", "#faq": "faq.html",
  "#card": "sign-card.html", "#involved": "get-involved.html", "#hero": "index.html", "#top": "index.html",
};
function mapHashes(html) {
  html = html.split('href="#cardForm"').join('href="sign-card.html#cardForm"');
  for (const [h, f] of Object.entries(HASH)) html = html.split('href="' + h + '"').join('href="' + f + '"');
  return html; // leaves href="#" and href="#main" untouched
}
// Home carries the about/issues/voices sections itself, so keep those as
// same-page anchors and only send links to OTHER pages out to their files.
function mapHashesHome(html) {
  html = html.split('href="#cardForm"').join('href="sign-card.html#cardForm"');
  const M = { "#international": "international.html", "#committee": "committee.html", "#faq": "faq.html",
              "#card": "sign-card.html", "#involved": "get-involved.html" };
  for (const [h, f] of Object.entries(M)) html = html.split('href="' + h + '"').join('href="' + f + '"');
  return html;
}
/* ---- shared header with active-tab state ---- */
const NAV = [
  { key: "home", label: "Home", href: "index.html" },
  { key: "international", label: "International students", href: "international.html" },
  { key: "committee", label: "Committee", href: "committee.html" },
  { key: "faq", label: "FAQ", href: "faq.html" },
];
function navLinksHtml(active) {
  const links = NAV.map((i) =>
    `        <a class="nav__link" href="${i.href}"${active === i.key ? ' aria-current="page"' : ""}>${i.label}</a>`
  ).join("\n");
  const cta = `\n        <a class="nav__cta" href="sign-card.html"${active === "card" ? ' aria-current="page"' : ""}>Sign your card</a>`;
  return `<div class="nav__links" id="nav-links">\n${links}${cta}\n      </div>`;
}
function header(active) {
  let h = parsedHeader.replace(/<div class="nav__links"[\s\S]*?<\/div>/, () => navLinksHtml(active));
  // SAW brand is a plain, non-clickable label (not a link)
  h = h.replace(/<a class="brand" href="#top"([^>]*)>/, '<span class="brand"$1>');
  h = h.replace("</a>", "</span>");
  return h;
}
const footer = mapHashes(parsedFooter);

const FAVICON = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='18' fill='%2398303d'/%3E%3Ctext x='50' y='68' font-family='Arial' font-weight='900' font-size='52' fill='white' text-anchor='middle'%3E✊%3C/text%3E%3C/svg%3E";

function layout({ title, desc, active, main }) {
  return `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>${title}</title>
  <meta name="description" content="${desc}" />
  <meta name="theme-color" content="#ffffff" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
  <link rel="icon" href="${FAVICON}" />
  <script>document.documentElement.classList.add('js');</script>
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>
  ${header(active)}
  <main id="main">
${main}
  </main>
  ${footer}
  <script src="script.js" defer></script>
</body>
</html>
`;
}

/* ---- authored bits for the home hub ---- */
const CTA_BAND = `
    <section class="cta-band">
      <div class="container cta-band__inner">
        <div>
          <h2 class="cta-band__title">Ready to be part of it?</h2>
          <p class="cta-band__text">Signing your card is confidential and takes two minutes.</p>
        </div>
        <a class="btn btn--light" href="sign-card.html">Sign your card</a>
      </div>
    </section>`;

const EXPLORE = `
    <section class="section explore-section">
      <div class="container">
        <div class="section__head">
          <p class="section__eyebrow">The campaign</p>
          <h2 class="section__title">Explore</h2>
        </div>
        <div class="explore-grid">
          <a class="explore-card" href="international.html"><h3>International students &rarr;</h3><p>Support for international students, visa security, and research funding.</p></a>
          <a class="explore-card" href="committee.html"><h3>Committee &rarr;</h3><p>Meet the people leading the campaign.</p></a>
          <a class="explore-card" href="faq.html"><h3>FAQ &rarr;</h3><p>Confidential? Free? Safe for international workers? Answers here.</p></a>
        </div>
      </div>
    </section>`;

const HOME_MAIN = mapHashesHome(heroSection) + "\n" + marquee + "\n" +
  mapHashesHome(S.about + S.issues + S.voices) + "\n" + EXPLORE + "\n" + CTA_BAND;

/* ---- pages ---- */
const PAGES = [
  { file: "index.html", active: "home", title: "SAW: Stevens Academic Workers",
    desc: "480 students and researchers at Stevens organizing a union for a real voice over pay, benefits, and working conditions.", main: HOME_MAIN },
  { file: "international.html", active: "international", title: "International students | SAW",
    desc: "How a union contract protects international workers and secures research funding.", main: mapHashes(S.international) + CTA_BAND },
  { file: "committee.html", active: "committee", title: "Organizing committee | SAW",
    desc: "Meet the worker-led organizing committee behind the SAW campaign.", main: mapHashes(S.committee) + CTA_BAND },
  { file: "faq.html", active: "faq", title: "FAQ | SAW",
    desc: "Answers about union cards, confidentiality, dues, eligibility, and international workers.", main: mapHashes(S.faq) + CTA_BAND },
  { file: "sign-card.html", active: "card", title: "Sign your union card | SAW",
    desc: "Sign your union authorization card. Confidential, free, and the most important step.", main: mapHashes(S.card) },
  { file: "get-involved.html", active: "involved", title: "Get involved | SAW",
    desc: "Sign your card, join the organizing committee, and get campaign updates.", main: mapHashes(S.involved) },
];

let n = 0;
for (const p of PAGES) { fs.writeFileSync(DIR + "/" + p.file, layout(p)); n++; console.log("wrote", p.file); }

// about.html redirects to the merged home page, so old links keep working
fs.writeFileSync(DIR + "/about.html",
  '<!DOCTYPE html>\n<html lang="en"><head><meta charset="UTF-8" />\n' +
  '<meta http-equiv="refresh" content="0; url=index.html#about" />\n' +
  '<title>About | SAW</title>\n' +
  '</head><body><p>The about page moved to the <a href="index.html#about">home page</a>.</p></body></html>\n');
console.log("wrote about.html (redirect)");
console.log("done:", n, "pages + redirect");
