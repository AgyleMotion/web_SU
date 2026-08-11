/* =============================================================================
   ASTRA - interactions
   Progressive enhancement: the site is fully usable without JS. This adds the
   mobile menu, scroll reveals, stat count-up, header shadow, and friendly
   client-side form handling.
   ========================================================================== */
(function () {
  "use strict";

  /* ---- Footer year ---- */
  var yearEl = document.getElementById("year");
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  /* ---- Mobile nav toggle ---- */
  var toggle = document.querySelector(".nav__toggle");
  var links = document.getElementById("nav-links");
  if (toggle && links) {
    toggle.addEventListener("click", function () {
      var open = links.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", String(open));
      toggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
    });
    // Close the menu after tapping a link (mobile)
    links.addEventListener("click", function (e) {
      if (e.target.closest("a") && links.classList.contains("is-open")) {
        links.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
        toggle.setAttribute("aria-label", "Open menu");
      }
    });
  }

  /* ---- Header shadow on scroll ---- */
  var header = document.querySelector(".site-header");
  if (header) {
    var onScroll = function () {
      header.classList.toggle("is-stuck", window.scrollY > 8);
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  /* ---- FAQ: keep only one item open at a time ---- */
  var faqItems = document.querySelectorAll(".faq__item");
  faqItems.forEach(function (item) {
    item.addEventListener("toggle", function () {
      if (item.open) {
        faqItems.forEach(function (other) {
          if (other !== item) other.open = false;
        });
      }
    });
  });

  /* ---- Reveal on scroll + stat count-up (IntersectionObserver) ---- */
  var prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var revealEls = document.querySelectorAll(".reveal");
  var statEls = document.querySelectorAll(".stat__num[data-count]");

  if ("IntersectionObserver" in window && !prefersReduced) {
    var revealObserver = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: "0px 0px -40px 0px" });
    revealEls.forEach(function (el) { revealObserver.observe(el); });

    var statObserver = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          countUp(entry.target);
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    statEls.forEach(function (el) { statObserver.observe(el); });
  } else {
    // No IO / reduced motion: just show everything at final values
    revealEls.forEach(function (el) { el.classList.add("is-visible"); });
    statEls.forEach(function (el) {
      el.textContent = (+el.dataset.count).toLocaleString() + (el.dataset.suffix || "");
    });
  }

  function countUp(el) {
    var target = +el.dataset.count;
    var suffix = el.dataset.suffix || "";
    var dur = 1400;
    var start = performance.now();
    function tick(now) {
      var p = Math.min((now - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3); // easeOutCubic
      el.textContent = Math.round(target * eased).toLocaleString() + suffix;
      if (p < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }

  /* ---------------------------------------------------------------------------
     FORM HANDLING (front-end demo)
     -------------------------------------------------------------------------
     IMPORTANT FOR LIVE USE: a union authorization card is a confidential, legally
     meaningful document. Do NOT collect real cards through a plain page like this.
     Connect this form to your union's OFFICIAL secure card platform instead, e.g.:
       - your national union's card-signing system (UAW / AFT / etc.)
       - Action Network ("sign the card" action)
       - a vetted, access-controlled form owned by the organizing committee
     Replace the success simulation below with a real submission to that system.
  --------------------------------------------------------------------------- */
  var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  function setNote(noteEl, msg, kind) {
    if (!noteEl) return;
    noteEl.textContent = msg;
    noteEl.classList.remove("is-ok", "is-err");
    if (kind) noteEl.classList.add(kind === "ok" ? "is-ok" : "is-err");
  }

  /* Authorization card form */
  var cardForm = document.getElementById("cardForm");
  var formNote = document.getElementById("formNote");
  if (cardForm) {
    cardForm.addEventListener("submit", function (e) {
      e.preventDefault();
      var name = cardForm.elements["name"].value.trim();
      var email = cardForm.elements["email"].value.trim();
      var authorized = cardForm.elements["authorize"].checked;

      if (!name) { setNote(formNote, "Please add your name.", "err"); cardForm.elements["name"].focus(); return; }
      if (!emailRe.test(email)) { setNote(formNote, "Please enter a valid email. Use a personal address, not @stevens.edu.", "err"); cardForm.elements["email"].focus(); return; }
      if (/@stevens\.edu$/i.test(email)) { setNote(formNote, "For your privacy, please use a personal email, not your Stevens address.", "err"); cardForm.elements["email"].focus(); return; }
      if (!authorized) { setNote(formNote, "Please check the box to authorize representation.", "err"); return; }

      // TODO (CUSTOMIZE): submit to your union's official secure card system here.
      cardForm.innerHTML =
        '<div class="card-form__done">' +
        '<div style="font-size:2.4rem;line-height:1;color:var(--red)">✓</div>' +
        '<h3 class="card-form__title" style="margin-top:10px">Thank you, ' + escapeHtml(name.split(" ")[0]) + '!</h3>' +
        '<p style="color:var(--ink-soft)">Your card has been recorded by the organizing committee. ' +
        'When you\'re ready, invite a coworker to do the same.</p>' +
        '<a class="btn btn--primary btn--block" href="#involved" style="margin-top:8px">Get more involved</a>' +
        '</div>';
      cardForm.classList.add("is-done");
    });
  }

  /* Email list signup */
  var listForm = document.getElementById("listForm");
  var listNote = document.getElementById("listNote");
  if (listForm) {
    listForm.addEventListener("submit", function (e) {
      e.preventDefault();
      var email = listForm.elements["email"].value.trim();
      if (!emailRe.test(email)) { setNote(listNote, "Please enter a valid email.", "err"); return; }
      // TODO (CUSTOMIZE): connect to your email/list provider.
      listForm.elements["email"].value = "";
      setNote(listNote, "You're on the list. Thanks for being here.", "ok");
    });
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
    });
  }
})();
