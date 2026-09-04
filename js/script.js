/* ============================================================
   ART TERRE CREATIONS — script.js
   Interactivity: nav, filters, cart, story toggles, forms
   ============================================================ */

"use strict";

/* ---------- Helpers ---------- */
const $ = (selector, scope = document) => scope.querySelector(selector);
const $$ = (selector, scope = document) => [...scope.querySelectorAll(selector)];

/* ============================================================
   1. MOBILE NAVIGATION (hamburger)
   ============================================================ */
const hamburger = $("#hamburger");
const navMenu = $("#nav-menu");

function closeMenu() {
  hamburger.classList.remove("active");
  navMenu.classList.remove("open");
  hamburger.setAttribute("aria-expanded", "false");
}

hamburger.addEventListener("click", () => {
  const isOpen = navMenu.classList.toggle("open");
  hamburger.classList.toggle("active", isOpen);
  hamburger.setAttribute("aria-expanded", String(isOpen));
});

$$(".nav-link").forEach((link) =>
  link.addEventListener("click", closeMenu)
);

document.addEventListener("click", (e) => {
  if (
    navMenu.classList.contains("open") &&
    !navMenu.contains(e.target) &&
    !hamburger.contains(e.target)
  ) {
    closeMenu();
  }
});

/* ============================================================
   2. ACTIVE NAV LINK ON SCROLL (scroll-spy)
   ============================================================ */
const sections = $$("main section[id], footer[id]");
const navLinks = $$(".nav-link");

const spy = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const id = entry.target.id;
        navLinks.forEach((link) =>
          link.classList.toggle("active", link.getAttribute("href") === `#${id}`)
        );
      }
    });
  },
  { rootMargin: "-45% 0px -50% 0px" }
);

sections.forEach((sec) => spy.observe(sec));

/* ============================================================
   3. ARTWORK CATEGORY FILTERS (All / Painting / Photography)
   ============================================================ */
const filterBtns = $$(".filter-btn");
const artworkCards = $$(".artwork-card");

filterBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    filterBtns.forEach((b) => b.classList.remove("active"));
    btn.classList.add("active");

    const filter = btn.dataset.filter;

    artworkCards.forEach((card) => {
      const match = filter === "all" || card.dataset.category === filter;
      card.classList.toggle("hide", !match);
    });
  });
});

/* ============================================================
   4. LIKE BUTTONS (artists + artworks)
   ============================================================ */
$$(".like-btn").forEach((btn) => {
  btn.addEventListener("click", () => {
    btn.classList.toggle("liked");
    btn.style.color = btn.classList.contains("liked") ? "#c97b63" : "";
  });
});

/* ============================================================
   5. STORY TOGGLES ("Story." pill on artwork cards)
   ============================================================ */
$$(".story-toggle").forEach((toggle) => {
  toggle.addEventListener("click", () => {
    const card = toggle.closest(".artwork-card");
    const story = $(".artwork-story", card);
    const expanded = toggle.getAttribute("aria-expanded") === "true";

    story.hidden = expanded;
    toggle.setAttribute("aria-expanded", String(!expanded));
    toggle.textContent = expanded ? "Story." : "Close ✕";
  });
});

/* ============================================================
   6. SHOPPING CART (header count + card add buttons)
   ============================================================ */
const cartCount = $("#cart-count");
let cartItems = 0;

function addToCart() {
  cartItems++;
  cartCount.textContent = cartItems;

  // little bounce on the header cart
  const cartBtn = $("#cart-btn");
  cartBtn.animate(
    [
      { transform: "scale(1)" },
      { transform: "scale(1.2)" },
      { transform: "scale(1)" }
    ],
    { duration: 300, easing: "ease" }
  );
}

$$(".card-cart").forEach((btn) =>
  btn.addEventListener("click", addToCart)
);

$("#cart-btn").addEventListener("click", () => {
  cartCount.animate(
    [
      { transform: "scale(1)" },
      { transform: "scale(1.3)" },
      { transform: "scale(1)" }
    ],
    { duration: 300, easing: "ease" }
  );
});

/* ============================================================
   7. FORM VALIDATION (sign-in, newsletter, contact)
   ============================================================ */
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function setMsg(el, text, type) {
  el.textContent = text;
  el.classList.remove("success", "error");
  if (type) el.classList.add(type);
}

/* --- 7a. Header sign-in --- */
const signinForm = $("#signin-form");
const signinMsg = $("#signin-msg");

signinForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const email = $("#signin-email").value.trim();

  if (!EMAIL_RE.test(email)) {
    setMsg(signinMsg, "Enter a valid email.", "error");
    return;
  }

  setMsg(signinMsg, `Welcome, ${email}! (demo)`, "success");
  signinForm.reset();
});

/* Clear the in-box message as soon as the user types again */
signinForm.addEventListener("input", () => {
  signinMsg.textContent = "";
});

/* --- 7b. Newsletter (Join Our Creative Community) --- */
const newsForm = $("#newsletter-form");
const newsMsg = $("#newsletter-msg");

newsForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const email = $("#newsletter-email").value.trim();

  if (!EMAIL_RE.test(email)) {
    setMsg(newsMsg, "Please enter a valid email address.", "error");
    return;
  }

  setMsg(newsMsg, "You're in! Watch your inbox for new artworks. 🎨", "success");
  newsForm.reset();
});

/* --- 7c. Contact form (footer) --- */
const contactForm = $("#contact-form");
const contactMsg = $("#contact-msg");

contactForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const name = $("#c-name").value.trim();
  const email = $("#c-email").value.trim();
  const message = $("#c-message").value.trim();

  if (name.length < 2) {
    setMsg(contactMsg, "Please enter your name.", "error");
    return;
  }
  if (!EMAIL_RE.test(email)) {
    setMsg(contactMsg, "Please enter a valid email address.", "error");
    return;
  }
  if (message.length < 10) {
    setMsg(contactMsg, "Message should be at least 10 characters.", "error");
    return;
  }

  setMsg(contactMsg, `Thanks, ${name}! Your message has been sent. (demo)`, "success");
  contactForm.reset();
});

/* ============================================================
   8. SCROLL REVEAL ANIMATIONS
   ============================================================ */
const revealObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        revealObserver.unobserve(entry.target);
      }
    });
  },
  { threshold: 0.12 }
);

$$(".reveal").forEach((el) => revealObserver.observe(el));

/* ============================================================
   9. BACK TO TOP
   ============================================================ */
const toTop = $("#to-top");

window.addEventListener(
  "scroll",
  () => toTop.classList.toggle("show", window.scrollY > 500),
  { passive: true }
);

toTop.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});

/* ============================================================
   NEXT STEPS (prepared for future work)
   ------------------------------------------------------------
   - Replace .art CSS placeholders with real <img> artwork files
   - Replace artist initials with real portrait photos
   - Connect forms to a backend / email service
   - Build a cart drawer/modal + checkout for the $1.99 purchases
   - Persist cart & likes with localStorage
   ============================================================ */