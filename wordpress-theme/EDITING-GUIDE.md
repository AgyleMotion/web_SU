# Editing the ASTRA website

A plain-language guide for committee members. You never need to touch code. Everything below happens in the WordPress dashboard (your site address followed by `/wp-admin`).

After any change, click **Save**, **Update** or **Publish**, then open the site in a new tab to check it. If you do not see your change, refresh the page.

---

## First-time setup (once, after the theme is installed)

1. Go to **Appearance → ASTRA starter content** and click **Load starter content**.
2. This loads the whole current website: all pages, testimonials with photos, FAQ answers, issue cards and menus.
3. It is safe to click again: it never overwrites anything that already exists.

---

## Testimonials

Left menu: **Testimonials**.

**Add one:** click **Add new testimonial**.
- **Title:** the person's name, e.g. *Sara Bender-Bier*.
- **Main text area:** their quote. Include the quotation marks if you want them shown.
- **Testimonial details** box (below the text): their **Department**.
- **Photo:** in the right sidebar, click **Set featured image** and upload a headshot. Any size works; it is shown as a small circle. With no photo, a circle with their initials is shown instead.
- Click **Publish**.

**Edit or remove one:** click its name in the list. To remove it, click **Move to trash**.

**Change the order:** open a testimonial, find **Order** in the right sidebar (under *Page attributes*), and give it a number. Lower numbers show first (0, 1, 2…).

---

## Departments and organizers

Left menu: **Departments**. These appear on the Testimonials page under "Organizing across departments".

- The departments were loaded as **drafts**, so that section is hidden, matching the current site. To show it, open each department and click **Publish**. The section appears as soon as one department is published.
- **Title:** the department name.
- **Organizers** box: the names, separated by commas, e.g. *Nima Kalantari, Jett Langhorn*.
- **Order** (right sidebar) sets the position, lowest first.

The heading and intro above the list are on the Testimonials page itself: **Pages → Testimonials**, in the **Section heading** box at the bottom.

---

## FAQ

Left menu: **FAQ**.

- **Title:** the question.
- **Main text area:** the answer. You can add links and bold text.
- **Order** (right sidebar) sets the position, lowest first.

---

## Issue cards ("What we're working toward" on the home page)

Left menu: **Issues**.

- **Title:** the card heading, e.g. *Fair, livable pay*.
- **Main text area:** the short description.
- **Card icon** box: paste one emoji, e.g. 💵
- **Order** (right sidebar) sets the position, lowest first.

---

## Home page headline, stats and other home page text

**Appearance → Customize → ASTRA Campaign**. You see a live preview as you type, and nothing changes on the real site until you click **Publish** at the top.

- **Home: hero and stats:** the big headline, the text under it, both buttons, and the **three numbers** (e.g. *441 PhD students reached*). Type only the number in the number boxes; the count-up animation is automatic.
- **Home: sections:** the headings on the home page, the four "About" boxes, the "Without a union / With one" lists (one point per line), and the three "Explore" cards.
- **Red "Ready to be part of it?" band:** the red box near the bottom of most pages.
- **Footer:** contact email, the "Follow" links (Instagram, Bluesky…), the disclaimer and the copyright line.
- **Card signing:** see *Before launch* below.

The "Who we are" paragraphs on the home page are regular page text: **Pages → Home**.

**Headline tips:** type `<br />` for a line break, and wrap a word like `<span class="u-mark">say</span>` to get the red underline.

**Links in these settings** can be a full address (`https://…`), a page path like `/faq/`, or a jump to a section of the home page like `#issues`.

---

## The menu (tabs at the top) and footer link lists

**Appearance → Menus**.

- Choose **Main menu** from the drop-down at the top to edit the header tabs.
- **Rename** a tab: click the arrow on its row and change **Navigation label**.
- **Reorder:** drag the rows up and down.
- **Add a page:** tick it in the **Pages** box on the left, then **Add to menu**.
- **Add a link to a home page section** (like About): open **Custom links**, enter the URL (e.g. `https://your-site/#about`) and a label.
- **Remove:** open the row and click **Remove**.
- Click **Save menu**.

**The red "Sign your card" button:** it is a normal menu item with the CSS class `nav__cta`. If you rebuild it and need to set that class, click **Screen options** (top right of the Menus screen), tick **CSS classes**, then type `nav__cta` into that item's CSS classes field.

The footer lists work the same way: pick the **Campaign** or **Get involved** menu from the drop-down. The menu's name is used as the column heading. The contact email at the bottom of "Get involved" comes from **Customize → ASTRA Campaign → Footer**.

---

## Adding a brand-new page

1. **Pages → Add new page**.
2. Type a title and your content. Paragraphs, headings, lists, images and quotes all pick up the site's style automatically.
3. Optional: the **Section heading** box at the bottom lets you add a small label above the heading, a different big heading, and an intro sentence.
4. Click **Publish**.
5. To put it in the menu, see *The menu* above.

---

## Editing the text of the other pages

Open **Pages** and click the page. The main text is in the editor; the heading, small label and intro are in the **Section heading** box at the bottom.

- **International students:** the three cards and the yellow note are in the editor. Click into a card to change its words. Please do not delete the grey outline boxes around the cards; they hold the layout.
- **Sign your card:** the intro paragraph and the confidentiality note are in the editor. The three numbered steps are in the **Numbered steps** field (one step per line).
- **Get involved:** the intro is in the editor. The email box heading and text are in the **Section heading** box.
- **Meet Lincoln:** the text is in the editor. To change his photo, use **Set featured image** in the right sidebar. The heart counter keeps working on its own.

---

## Logo and browser icon

**Appearance → Customize → Site Identity**.

- **Logo:** click **Select logo** and upload. The site uses the bundled ASTRA-UAW logo until you set one here. Use a wide PNG with a transparent background, about 840 pixels wide, for the sharpest result.
- **Site icon:** the small icon in the browser tab.

---

## Before launch: card signing

Union cards are confidential and legally important, so **this website never collects them**. The form on the Sign your card page is a placeholder and does not save or send anything.

When the campaign has the link to UAW's official, secure card platform, paste it into **Customize → ASTRA Campaign → Card signing → Official card platform link** and click **Publish**. The placeholder form is then replaced with a button that takes people to the secure platform.

The "Keep me posted" email box on the Get involved page is also a placeholder until it is connected to a mailing list service.
