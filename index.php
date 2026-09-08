<?php
/* ============================================================
   ART TERRE CREATIONS — Landing page
   Shared <head>, header/nav and footer live in /includes
   ============================================================ */
$pageTitle = "Art Terre Creations — Where Art Meets Opportunity";
$page = "home";
include "includes/header.php";
?>

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
              <button class="like-btn" type="button" aria-label="Like Alexander The Great" aria-pressed="false">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              </button>
              <h3 class="artist-name">Alexander The Great</h3>
            </div>
          </article>

          <article class="artist-card reveal">
            <div class="artist-photo"><span class="artist-initials">SD</span></div>
            <div class="artist-foot">
              <button class="like-btn" type="button" aria-label="Like Sean Da Vinci" aria-pressed="false">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              </button>
              <h3 class="artist-name">Sean Da Vinci</h3>
            </div>
          </article>

          <article class="artist-card reveal">
            <div class="artist-photo"><span class="artist-initials">RS</span></div>
            <div class="artist-foot">
              <button class="like-btn" type="button" aria-label="Like Rembrandt Sean Rijn" aria-pressed="false">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              </button>
              <h3 class="artist-name">Rembrandt Sean Rijn</h3>
            </div>
          </article>
        </div>

        <div class="section-cta reveal">
          <a href="artists.php" class="btn btn-outline">View All Artists</a>
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
            <p class="artwork-story">
              Painted in the quiet hours after midnight — a moonlit apology
              written in deep indigo, shadow, and silver light.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$1.99</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout “Sorry Night”">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add “Sorry Night” to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- Card 2 -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media"><span class="art art-mona"></span></div>
            <h3 class="artwork-title">“Mona Liza”</h3>
            <p class="artwork-artist">by <strong>Sean Da Vinci</strong></p>
            <p class="artwork-story">
              A modern homage to the world's most famous smile —
              reimagined in warm earth tones and soft Renaissance haze.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$1.99</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout “Mona Liza”">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add “Mona Liza” to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- Card 3 -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media"><span class="art art-storm"></span></div>
            <h3 class="artwork-title">"Christ In The Storm On The Sea Of Galilee"</h3>
            <p class="artwork-artist">by <strong>Rembrandt Sean Rijn</strong></p>
            <p class="artwork-story">
              A dramatic seascape capturing the exact moment faith meets fear —
              light breaks through the storm just as the waves reach their peak.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$1.99</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout “Christ In The Storm”">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add “Christ In The Storm” to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- Card 3 (painting) ends the featured row -->

        </div>

        <div class="section-cta reveal">
          <a href="artworks.php" class="btn btn-outline">View All Artworks</a>
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

<?php include "includes/footer.php"; ?>