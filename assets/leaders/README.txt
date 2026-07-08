PROFILE PHOTOS — ORGANIZING COMMITTEE
=====================================

>>> Drop each leader's photo in THIS folder:
        union-website/assets/leaders/

The website (see the #committee section in index.html) looks for these
exact filenames:

    amara.jpg    ->  Amara Okafor    (Lead Organizer)
    diego.jpg    ->  Diego Martinez  (Co-Chair)
    priya.jpg    ->  Priya Nair      (International Workers Liaison)
    sam.jpg      ->  Sam Whitfield   (Communications)
    leila.jpg    ->  Leila Haddad    (Bargaining Committee)
    jordan.jpg   ->  Jordan Lee      (Department Steward)

Save a person's photo with the matching name above and it appears
automatically. No photo yet? The card shows that person's initials
instead, so the page never looks broken.

USING YOUR OWN PEOPLE
    Edit the #committee section of index.html: change each
      - <img src="assets/leaders/NAME.jpg" alt="Full Name">
      - data-initials="XX"   (the initials shown when there's no photo)
      - name / role / department text
    Then name your image files to match the src.
    Add or delete an <article class="leader"> block to add/remove people.

IMAGE TIPS
    Shape   : square (1:1) — it's cropped to a circle automatically
    Size    : ~400-800 px per side is plenty
    Format  : .jpg, .png, or .webp
              (if you use .png/.webp, change the extension in the
               matching <img src> line in index.html)
    Weight  : keep each under ~300 KB so the page stays fast

TIP: for a consistent look, crop everyone to a square with the face
centered before adding them here.
