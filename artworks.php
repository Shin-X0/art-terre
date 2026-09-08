<?php
/* ============================================================
   ART TERRE — All Artworks page (with category filters)
   ============================================================ */
$pageTitle = "All Artworks — Art Terre Creations";
$page = "artworks";
include "includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">All Artworks</h1>
        <p class="page-hero-sub reveal">
          A growing collection of original paintings and photography —
          every piece is affordable, so great art stays within reach.
        </p>
      </div>
    </section>

    <!-- ======================= ARTWORK GRID ======================= -->
    <section class="artworks section" id="artworks">
      <div class="container">
        <!-- Category filters (wired up in js/script.js) -->
        <div class="filters reveal" id="filters" aria-label="Filter artworks by category">
          <button class="filter-btn active" data-filter="all" type="button">All</button>
          <button class="filter-btn" data-filter="painting" type="button">Painting</button>
          <button class="filter-btn" data-filter="photography" type="button">Photography</button>
        </div>

        <div class="artwork-grid" id="artwork-grid">

          <!-- 1. Painting -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media placeholder-square"></div>
            <h3 class="artwork-title">Sorry Night</h3>
            <p class="artwork-artist">by <strong>Alexander The Great</strong></p>
            <p class="artwork-story">
              Painted in the quiet hours after midnight — a moonlit apology
              written in deep indigo, shadow, and silver light.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$1.99</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout Sorry Night">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add Sorry Night to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- 2. Painting -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media placeholder-square"></div>
            <h3 class="artwork-title">Mona Liza</h3>
            <p class="artwork-artist">by <strong>Sean Da Vinci</strong></p>
            <p class="artwork-story">
              A modern homage to the world's most famous smile —
              reimagined in warm earth tones and soft Renaissance haze.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$1.99</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout Mona Liza">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add Mona Liza to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- 3. Painting -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media placeholder-square"></div>
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
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout Christ In The Storm">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add Christ In The Storm to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- 4. Painting -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media placeholder-square"></div>
            <h3 class="artwork-title">Golden Field Dusk</h3>
            <p class="artwork-artist">by <strong>Maya Solene</strong></p>
            <p class="artwork-story">
              The last hour of a summer day, pressed onto canvas —
              wheat, warmth, and a sky that refuses to hurry.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$2.49</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout Golden Field Dusk">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add Golden Field Dusk to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- 5. Painting -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media placeholder-square"></div>
            <h3 class="artwork-title">Whispering Forest</h3>
            <p class="artwork-artist">by <strong>Theo Verdant</strong></p>
            <p class="artwork-story">
              Layers of sage and shadow — a forest that speaks
              only to those who stand still long enough to listen.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$2.99</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout Whispering Forest">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add Whispering Forest to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- 6. Painting -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media placeholder-square"></div>
            <h3 class="artwork-title">Clay &amp; Sun</h3>
            <p class="artwork-artist">by <strong>Lena Moreau</strong></p>
            <p class="artwork-story">
              Terracotta rooftops, baked earth, and an afternoon
              sun that turns every wall into a warm embrace.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$1.49</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout Clay & Sun">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add Clay & Sun to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- 7. Painting -->
          <article class="artwork-card reveal" data-category="painting">
            <div class="artwork-media placeholder-square"></div>
            <h3 class="artwork-title">Blue Harbor</h3>
            <p class="artwork-artist">by <strong>Kofi Mensah</strong></p>
            <p class="artwork-story">
              Fishing boats at rest, painted in every blue the
              ocean owns — a small port with a big, calm heart.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$2.19</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout Blue Harbor">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add Blue Harbor to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- 8. Photography -->
          <article class="artwork-card reveal" data-category="photography">
            <div class="artwork-media placeholder-square"></div>
            <h3 class="artwork-title">Morning Mist</h3>
            <p class="artwork-artist">by <strong>Isla Fern</strong></p>
            <p class="artwork-story">
              Captured at 5:47 am, just before the sun burned it away —
              the valley breathing out the last of the night.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$1.29</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout Morning Mist">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add Morning Mist to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- 9. Photography -->
          <article class="artwork-card reveal" data-category="photography">
            <div class="artwork-media placeholder-square"></div>
            <h3 class="artwork-title">City Pulse</h3>
            <p class="artwork-artist">by <strong>Aria Kobayashi</strong></p>
            <p class="artwork-story">
              A long exposure of rush hour — headlights turned into
              rivers of light, the city showing its heartbeat.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$1.79</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout City Pulse">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add City Pulse to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- 10. Photography -->
          <article class="artwork-card reveal" data-category="photography">
            <div class="artwork-media placeholder-square"></div>
            <h3 class="artwork-title">Salt &amp; Sky</h3>
            <p class="artwork-artist">by <strong>Isla Fern</strong></p>
            <p class="artwork-story">
              Where the salt flats meet the horizon, the world
              forgets which way is up — and that is the point.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$2.09</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout Salt & Sky">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add Salt & Sky to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- 11. Photography -->
          <article class="artwork-card reveal" data-category="photography">
            <div class="artwork-media placeholder-square"></div>
            <h3 class="artwork-title">The Old Lens</h3>
            <p class="artwork-artist">by <strong>Rembrandt Sean Rijn</strong></p>
            <p class="artwork-story">
              Shot on a 40-year-old lens with a scratched front element —
              the flaws are what give the light its gentle bloom.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$0.99</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout The Old Lens">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add The Old Lens to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

          <!-- 12. Photography -->
          <article class="artwork-card reveal" data-category="photography">
            <div class="artwork-media placeholder-square"></div>
            <h3 class="artwork-title">Northern Light</h3>
            <p class="artwork-artist">by <strong>Maya Solene</strong></p>
            <p class="artwork-story">
              Three nights of waiting in the cold for twenty seconds
              of green fire — worth every shiver.
            </p>
            <div class="artwork-actions">
              <button class="story-toggle" type="button" aria-expanded="false">Story.</button>
              <div class="artwork-buy">
                <span class="artwork-price">$2.59</span>
                <button class="btn btn-accent card-checkout" type="button" aria-label="Checkout Northern Light">Checkout</button>
                <button class="card-cart" type="button" aria-label="Add Northern Light to cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
                    <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
                  </svg>
                </button>
              </div>
            </div>
          </article>

        </div>

        <div class="section-cta reveal">
          <a href="artists.php" class="btn btn-outline">View All Artists</a>
        </div>
      </div>
    </section>

<?php include "includes/footer.php"; ?>