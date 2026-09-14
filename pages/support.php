<?php
/* ============================================================
   ART TERRE — Support & Help page
   ============================================================ */
$pageTitle = "Support & Help — Art Terre Creations";
$page = "support";
include __DIR__ . "/../includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Support &amp; Help</h1>
        <p class="page-hero-sub reveal">
          Need a hand? Here's how to reach us and quick answers to the
          things people ask about most.
        </p>
      </div>
    </section>

    <!-- ======================= SUPPORT ======================= -->
    <section class="section" id="support">
      <div class="container">

        <div class="info-page">

          <div class="info-card reveal">
            <h2>📮 Get in touch</h2>
            <ul class="info-list">
              <li><strong>Contact form</strong> — the fastest way: use the
                <a href="../index.php#contact">Contact Us form</a> at the bottom of every page.
                We reply within <strong>1–2 business days</strong>.</li>
              <li><strong>Email</strong> — support@artterre.com (demo address).</li>
              <li><strong>Social</strong> — message us on Instagram, LinkedIn, or Facebook
                (links in the footer).</li>
            </ul>
          </div>

          <div class="info-card reveal">
            <h2>🧭 Quick how-to</h2>
            <ul class="info-list">
              <li><strong>Track an order</strong> — open <a href="orders.php">My orders</a>
                (header link when signed in). Statuses move from
                <em>Pending → Processing → Shipped → Delivered</em>.</li>
              <li><strong>Order details</strong> — click “View order details” on any order
                to see items, total, and payment instructions.</li>
              <li><strong>Sell your art</strong> — register with the
                <strong>🖌️ Artist</strong> role, then use <em>Sell artwork</em> in the
                header to upload pieces (JPG/PNG/WebP/GIF, up to 5&nbsp;MB).</li>
              <li><strong>Manage listings</strong> — artists can remove any of their
                uploaded artworks from the Sell artwork page at any time.</li>
              <li><strong>Locked out?</strong> — after 5 failed sign-in attempts the
                login locks for 5 minutes. Wait, then try again with the right
                password.</li>
            </ul>
          </div>

          <div class="info-card reveal">
            <h2>💳 Payments &amp; orders</h2>
            <ul class="info-list">
              <li>We accept <strong>credit/debit card</strong> and
                <strong>cash on delivery</strong>. You choose at checkout.</li>
              <li>Card orders stay <em>Pending</em> until the payment is verified
                (this demo never charges a real card).</li>
              <li>Delivery is <strong>free</strong> on every order.</li>
              <li>Have an order number handy (e.g. <code>AT-XXXXXX</code>) when you
                write to us — it makes everything faster.</li>
            </ul>
          </div>

          <div class="info-card reveal">
            <h2>⚠️ Something broken?</h2>
            <p>
              If a page errors out, note what you clicked and (if possible) attach a
              screenshot via the contact form. Database hiccups usually mean the
              MySQL service in your XAMPP control panel isn't running.
            </p>
          </div>

        </div>

      </div>
    </section>

<?php include __DIR__ . "/../includes/footer.php"; ?>