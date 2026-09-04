<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Art Terre Creations — Where Art Meets Opportunity</title>
  <meta name="description" content="Art Terre is a sustainable platform bridging artists and collectors worldwide. Showcase authentic creativity and help every artist thrive." />

  <!-- Brand typefaces (Brand Guide 101):
       Headings — Space Grotesk Bold (Google Fonts, display=swap keeps loading fast)
       Body — Poppins (Google Fonts webfont substitute for Tw Cen MT, renders on all devices) -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css?v=7" />
</head>
<body>

  <!-- ======================= HEADER ======================= -->
  <header class="header" id="header">
    <nav class="navbar container">
      <!-- Logo (official brand image) -->
      <a href="#home" class="logo" aria-label="Art Terre Creations — Home">
        <img class="logo-img" src="images/logo.png" alt="Art Terre Creations logo" />
      </a>

      <ul class="nav-menu" id="nav-menu">
        <li class="nav-item"><a href="#home" class="nav-link active">Home</a></li>
        <li class="nav-item"><a href="#artists" class="nav-link">Artist</a></li>
        <li class="nav-item"><a href="#artworks" class="nav-link">Artworks</a></li>
        <li class="nav-item"><a href="#about" class="nav-link">About</a></li>
        <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
      </ul>

      <!-- Header sign-in (email + button), as in the mockup -->
      <div class="header-signin">
        <form class="signin-form" id="signin-form" novalidate>
          <input type="email" name="email" id="signin-email" placeholder="Enter your Email Address" autocomplete="email" required />
          <button type="submit" class="btn btn-accent">Sign in</button>
        </form>
        <p class="form-msg" id="signin-msg" role="status" aria-live="polite"></p>
      </div>

      <!-- Cart -->
      <button class="cart-btn" id="cart-btn" aria-label="Shopping cart">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
          <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
        </svg>
        <span class="cart-count" id="cart-count">0</span>
      </button>

      <button class="hamburger" id="hamburger" aria-label="Open navigation menu" aria-expanded="false" aria-controls="nav-menu">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </button>
    </nav>
  </header>

  <main>
    <!-- ======================= HERO ======================= -->
    <section class="hero" id="home">
      <div class="container hero-inner">
        <div class="hero-content reveal">
          <h1 class="hero-title">
            Bridging Artists<br />
            and Collectors<br />
            <span class="worldwide">Worldwide</span>
          </h1>
        </div>
      </div>
    </section>

    <!-- ======================= TOP ARTISTS ======================= -->
    <section class="artists section" id="artists">
      <div class="container">
        <h2 class="section-title reveal">Top Artists</h2>

        <div class="artist-grid">
          <article class="artist-card reveal">
            <div class="artist-photo"><span class="artist-initials">AG</span></div>
            <div class="artist-foot">
              <button class="like-btn" type="button" aria-label="Like Alexander The Great">♥</button>
              <h3 class="artist-name">Alexander The Great</h3>
            </div>
          </article>

          <article class="artist-card reveal">
            <div class="artist-photo"><span class="artist-initials">SD</span></div>
            <div class="artist-foot">
              <button class="like-btn" type="button" aria-label="Like Sean Da Vinci">♥</button>
              <h3 class="artist-name">Sean Da Vinci</h3>
            </div>
          </article>

          <article class="artist-card reveal">
            <div class="artist-photo"><span class="artist-initials">RS</span></div>
            <div class="artist-foot">
              <button class="like-btn" type="button" aria-label="Like Rembrandt Sean Rijn">♥</button>
              <h3 class="artist-name">Rembrandt Sean Rijn</h3>
            </div>
          </article>
        </div>

        <div class="section-cta reveal">
          <a href="#" class="btn btn-outline">View All Artists</a>
        </div>
      </div>
    </section>

    <!-- ======================= FEATURED ARTWORKS ======================= -->
    <section class="artworks section" id="artworks">
      <div class="container">
        <h2 class="section-title reveal">Featured Artworks</h2>

        <!-- Category filters (wired up in js/script.js) -->
        <div class="filters reveal" id="filters" aria-label="Filter artworks by category">
          <button class="filter-btn active" data-filter="all" type="button">All</button>
          <button class="filter-btn" data-filter="painting" type="button">Painting</button>
          <button class="filter-btn" data-filter="photography" type="button">Photography</button>
        </div>

        <div class="artwork-grid" id="artwork-grid">

          <!-- Card 1 -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media"><span class="art art-starry"></span></div>
            <h3 class="artwork-title">“Sorry Night”</h3>
            <p class="artwork-artist">by <strong>Alexander The Great</strong></p>
            <p class="artwork-story" hidden>
              Painted in the quiet hours after midnight — a moonlit apology
              written in deep indigo, shadow, and silver light.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <span class="artwork-price">$1.99</span>
              <button class="card-cart" type="button" aria-label="Add “Sorry Night” to cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                  <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                </svg>
              </button>
            </div>
          </article>

          <!-- Card 2 -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media"><span class="art art-mona"></span></div>
            <h3 class="artwork-title">“Mona Liza”</h3>
            <p class="artwork-artist">by <strong>Sean Da Vinci</strong></p>
            <p class="artwork-story" hidden>
              A modern homage to the world's most famous smile —
              reimagined in warm earth tones and soft Renaissance haze.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <span class="artwork-price">$1.99</span>
              <button class="card-cart" type="button" aria-label="Add “Mona Liza” to cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                  <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                </svg>
              </button>
            </div>
          </article>

          <!-- Card 3 -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media"><span class="art art-storm"></span></div>
            <h3 class="artwork-title">"Christ In The Storm On The Sea Of Galilee"</h3>
            <p class="artwork-artist">by <strong>Rembrandt Sean Rijn</strong></p>
            <p class="artwork-story" hidden>
              A dramatic seascape capturing the exact moment faith meets fear —
              light breaks through the storm just as the waves reach their peak.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <span class="artwork-price">$1.99</span>
              <button class="card-cart" type="button" aria-label="Add “Christ In The Storm” to cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                  <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                </svg>
              </button>
            </div>
          </article>

          <!-- Card 3 (painting) ends the featured row -->

        </div>

        <div class="section-cta reveal">
          <a href="#" class="btn btn-outline">View All Artworks</a>
        </div>
      </div>
    </section>

    <!-- ======================= ABOUT (+ NEWSLETTER BOX) ======================= -->
    <section class="about section" id="about">
      <div class="container">
        <h2 class="section-title light reveal">About</h2>
        <h3 class="about-sub reveal">Where Art Meets Opportunity</h3>
        <p class="reveal">
          Art Terre is a sustainable platform created to bridge artists and collectors
          worldwide. We showcase authentic creativity, celebrate diverse artistic voices,
          and create opportunities for artists to share their work and thrive.
        </p>
        <p class="reveal">
          Our goal is to make art more accessible while building meaningful connections
          between creators and the people who value their work. Through a thoughtful and
          trusted space, Art Terre brings creativity, authenticity, and community together.
        </p>

        <!-- Join Our Creative Community box -->
        <div class="community-box reveal">
          <div class="community-text">
            <h3>Join Our Creative Community!</h3>
            <p>Sign up to receive updates on new Artworks, Featured Artists, and more.</p>
          </div>
          <div class="community-form-wrap">
            <form class="newsletter-form" id="newsletter-form" novalidate>
              <input type="email" name="email" id="newsletter-email" placeholder="Enter your Email Address" autocomplete="email" required />
              <button type="submit" class="btn btn-accent">Sign in</button>
            </form>
            <p class="form-msg" id="newsletter-msg" role="status" aria-live="polite"></p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- ======================= FOOTER (cream, with Contact Us) ======================= -->
  <footer class="footer" id="contact">
    <div class="container footer-main">
      <div class="footer-brand reveal">
        <a href="#home" class="logo footer-logo" aria-label="Art Terre Creations — Home">
          <img class="logo-img" src="images/logo.png" alt="Art Terre Creations logo" />
        </a>

        <p class="footer-mission">
          To bridge artists and collectors worldwide by creating a sustainable platform
          that showcases authentic creativity and opens opportunities for every artist to thrive.
        </p>

        <div class="socials">
          <a href="#" aria-label="Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r="1.1" fill="currentColor" stroke="none"/></svg>
            Instagram
          </a>
          <a href="#" aria-label="LinkedIn">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.1V21h-4v-5.5c0-1.31-.02-3-1.83-3-1.83 0-2.11 1.43-2.11 2.9V21H9z"/></svg>
            LinkedIn
          </a>
          <a href="#" aria-label="Facebook">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.5-3.89 3.78-3.89 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.45 2.89h-2.33v6.99A10 10 0 0 0 22 12Z"/></svg>
            Facebook
          </a>
        </div>
      </div>

      <div class="footer-contact reveal">
        <h2>Contact Us</h2>
        <p>
          Have a question, want to collaborate, or simply want to learn more about
          Art Terre? We’d love to hear from you.
        </p>

        <form class="contact-form" id="contact-form" novalidate>
          <div class="line-field">
            <label for="c-name">Name :</label>
            <input type="text" id="c-name" name="name" autocomplete="name" required />
          </div>
          <div class="line-field">
            <label for="c-email">Email :</label>
            <input type="email" id="c-email" name="email" autocomplete="email" required />
          </div>
          <div class="line-field line-field--message">
            <label for="c-message">Message :</label>
            <input type="text" id="c-message" name="message" required />
          </div>
          <div class="line-field line-field--bare">
            <input type="text" id="c-message2" name="message2" aria-label="Message continued" />
          </div>
          <div class="contact-submit">
            <button type="submit" class="btn btn-accent">Send Us a Message</button>
          </div>
          <p class="form-msg" id="contact-msg" role="status" aria-live="polite"></p>
        </form>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container footer-bottom-inner">
        <div class="fb-left">
          <a href="#">Support & Help</a>
          <a href="#">FAQ</a>
        </div>
        <div class="fb-right">
          <a href="#">Terms of services</a>
          <span class="divider" aria-hidden="true"></span>
          <a href="#">Privacy Policy</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Back to top -->
  <button class="to-top" id="to-top" aria-label="Back to top">↑</button>

  <!-- JavaScript -->
  <script src="js/script.js"></script>
</body>
</html>