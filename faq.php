<?php
/* ============================================================
   ART TERRE — FAQ page (native <details> accordions)
   ============================================================ */
$pageTitle = "FAQ — Art Terre Creations";
$page = "faq";
include "includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Frequently Asked Questions</h1>
        <p class="page-hero-sub reveal">
          The short answers, before you even have to ask.
        </p>
      </div>
    </section>

    <!-- ======================= FAQ ======================= -->
    <section class="section" id="faq">
      <div class="container">

        <div class="info-page faq-list reveal">

          <details>
            <summary>What's the difference between a Collector and an Artist?</summary>
            <div class="faq-answer">
              <p><strong>Collectors</strong> browse, like, and buy original artworks.
              <strong>Artists</strong> do everything collectors do <em>plus</em> upload
              and sell their own works. You pick your role when creating an account.</p>
            </div>
          </details>

          <details>
            <summary>Do I need an account to check out?</summary>
            <div class="faq-answer">
              <p>Yes — you'll be asked to sign in or create an account before checkout.
              This ties the order to you so you can track its status in
              <a href="orders.php">My orders</a>.</p>
            </div>
          </details>

          <details>
            <summary>What payment methods can I use?</summary>
            <div class="faq-answer">
              <p>At checkout you choose between <strong>💳 card</strong> (credit or
              debit) and <strong>💵 cash on delivery</strong>. Card orders remain
              <em>Pending</em> until payment is verified; COD orders are paid in cash
              when the artwork arrives. This is a demo — no real card is ever charged.</p>
            </div>
          </details>

          <details>
            <summary>How do I track my order?</summary>
            <div class="faq-answer">
              <p>Right after checkout you land on your order page. Any time after
              that, open <a href="orders.php">My orders</a> from the header. Your order
              moves through <em>Pending → Processing → Shipped → Delivered</em>, and you
              can also revisit it with its order number (e.g. <code>AT-XXXXXX</code>).</p>
            </div>
          </details>

          <details>
            <summary>Why can't I log in?</summary>
            <div class="faq-answer">
              <p>For your security, sign-in locks for <strong>5 minutes</strong> after
              <strong>5 failed attempts</strong>. Wait for the timer shown on the login
              button, then try again. If you've forgotten your password, contact us via
              the <a href="index.php#contact">contact form</a>.</p>
            </div>
          </details>

          <details>
            <summary>How do I start selling my artwork?</summary>
            <div class="faq-answer">
              <p>Create an account (or ask us to switch your role) and choose
              <strong>🖌️ Artist</strong>. A <em>Sell artwork</em> button appears in the
              header — upload a title, category, price, the story behind the piece, and
              an image (JPG, PNG, WebP, or GIF up to 5&nbsp;MB). It goes live on the
              <a href="artworks.php">Artworks page</a> immediately.</p>
            </div>
          </details>

          <details>
            <summary>Can I remove an artwork I uploaded?</summary>
            <div class="faq-answer">
              <p>Yes. Open <em>Sell artwork</em>, find the piece under
              <strong>My Artworks</strong>, and press <em>Remove</em>. It's taken off the
              shop right away.</p>
            </div>
          </details>

          <details>
            <summary>How much does delivery cost?</summary>
            <div class="faq-answer">
              <p>Delivery is <strong>free</strong> on every order — the price you see is
              the price you pay.</p>
            </div>
          </details>

          <details>
            <summary>Can I return an artwork?</summary>
            <div class="faq-answer">
              <p>Original art is one of a kind, but if your piece arrives damaged,
              contact us within 7 days via the
              <a href="index.php#contact">contact form</a> with a photo and your order
              number, and we'll make it right.</p>
            </div>
          </details>

          <details>
            <summary>Still stuck?</summary>
            <div class="faq-answer">
              <p>Head to the <a href="support.php">Support &amp; Help</a> page or drop us
              a line through the <a href="index.php#contact">contact form</a> — we
              usually reply within 1–2 business days.</p>
            </div>
          </details>

        </div>

      </div>
    </section>

<?php include "includes/footer.php"; ?>