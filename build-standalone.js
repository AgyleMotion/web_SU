const fs = require('fs');

const SRC = "/Users/mohammadtawdik/Desktop/web_SU";
const SCR = "/private/tmp/claude-501/-Users-mohammadtawdik-Desktop-Portfoilo-AutoGraderAi-Testcase1-ME261-GradeScope-Canvas-testing/a209b90f-a68e-4c7a-8870-0687c19dfbf4/scratchpad";

const html = fs.readFileSync(SRC + "/onepage.html", "utf8"); // single-page source (index.html is now the multi-page home)
const css  = fs.readFileSync(SRC + "/styles.css", "utf8");
const js   = fs.readFileSync(SRC + "/script.js", "utf8");
const b64  = fs.readFileSync(SCR + "/anton.b64", "utf8").replace(/\s+/g, "");

// --- Make the file encoding-proof -------------------------------------------
// The Artifact wrapper may not guarantee a UTF-8 charset, and raw emoji/accents
// then render as mojibake. Convert non-ASCII to HTML numeric entities in markup,
// and to \u escapes in JS (HTML entities are NOT decoded inside <script>/<style>).
function htmlEntities(s) {
  let out = "";
  for (const ch of s) {              // iterates by code point (handles astral emoji)
    const cp = ch.codePointAt(0);
    out += cp > 127 ? "&#x" + cp.toString(16).toUpperCase() + ";" : ch;
  }
  return out;
}
function jsEscape(s) {
  let out = "";
  for (let i = 0; i < s.length; i++) { // iterate UTF-16 units (surrogates -> two \u)
    const c = s.charCodeAt(i);
    out += c > 127 ? "\\u" + c.toString(16).toUpperCase().padStart(4, "0") : s[i];
  }
  return out;
}

// Grab only the <body> contents; drop the external <script src> tag.
let bodyInner = html.split("<body>")[1].split("</body>")[0];
bodyInner = bodyInner.replace(/\s*<script src="script\.js"[^>]*><\/script>\s*/g, "\n");
bodyInner = htmlEntities(bodyInner);
const jsSafe = jsEscape(js);
const cssNonAscii = (css.match(/[^\x00-\x7F]/g) || []).length; // report only (CSS comments harmless)

const fontFace =
  "@font-face{font-family:'Anton';font-style:normal;font-weight:400;font-display:swap;" +
  "src:url(data:font/woff2;base64," + b64 + ") format('woff2');}";

// Commit to the poster identity: paint the wrapper the platform provides so the
// look is identical regardless of the viewer's light/dark preference.
const groundRule = "html,body{background:#faf6ef;margin:0;}body{color:#302c36;}";

const out =
  "<title>SAW: Stevens Academic Workers</title>\n" +
  "<style>\n" + fontFace + "\n" + groundRule + "\n" + css + "\n</style>\n" +
  "<script>document.documentElement.classList.add('js');</script>\n" +
  bodyInner + "\n" +
  "<script>\n" + jsSafe + "\n</script>\n";

const outPath = SCR + "/sraw-campaign.html";
fs.writeFileSync(outPath, out);

// Sanity report (don't dump the whole file — the font blob is large)
console.log("wrote:", outPath);
console.log("bytes:", out.length);
console.log("has <style>:", out.startsWith("<style>"));
console.log("font embedded:", out.includes("data:font/woff2;base64,"));
console.log("committee section:", out.includes('id="committee"'));
console.log("leader cards:", (out.match(/leader__name/g) || []).length);
console.log("card form:", out.includes('id="cardForm"'));
console.log("raw non-ASCII bytes left in file (should be 0):", (out.match(/[^\x00-\x7F]/g) || []).length);
console.log("css non-ASCII (comments only, harmless):", cssNonAscii);
console.log("fist entity present:", out.includes("&#x270A;"));
console.log("external font <link> present (should be false):", /fonts\.googleapis\.com/.test(out));
console.log("styles.css <link> present (should be false):", /href="styles\.css"/.test(out));
console.log("script.js <script src> present (should be false):", /src="script\.js"/.test(out));
