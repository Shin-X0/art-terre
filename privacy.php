<?php
/* ============================================================
   ART TERRE — Privacy Policy page
   ============================================================ */
$pageTitle = "Privacy Policy — Art Terre Creations";
$page = "privacy";
include "includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Privacy Policy</h1>
        <p class="page-hero-sub reveal">
          What we collect, why we collect it, and how it's protected.
        </p>
      </div>
    </section>

    <!-- ======================= PRIVACY ======================= -->
    <section class="section" id="privacy">
      <div class="container">

        <div class="info-page">

          <div class="info-card reveal">
            <p class="info-updated">Last updated: September 12, 2026</p>

            <h2>1. What we collect</h2>
            <ul class="info-list">
              <li><strong>Account details</strong> — your name, email address, date of
                birth, gender, and the role you choose (collector or artist). Passwords
                are stored only as strong one-way hashes — never in plain text.</li>
              <li><strong>Order details</strong> — billing name, email, delivery
                address, items purchased, payment method, and order status.</li>
              <li><strong>Artwork uploads</strong> — the images, titles, prices, and
                descriptions you publish as an artist.</li>
              <li><strong>Technical data</strong> — a session cookie that keeps you
                signed in (HttpOnly, valid 7 days).</li>
            </ul>

            <h2>2. What we deliberately don't collect</h2>
            <p>
              <strong>Card numbers, expiry dates, and CVV codes are validated for
              format and then discarded.</strong> They are never written to the
              database, log files, or anywhere else. Cash on delivery needs no card
              data at all.
            </p>

            <h2>3. How we use your information</h2>
            <ul class="info-list">
              <li>To create and secure your account (including the 5-attempt sign-in
                lockout).</li>
              <li>To process, deliver, and track your orders, and to show you live
                status updates.</li>
              <li>To display your uploaded artworks to potential buyers.</li>
              <li>To answer support requests you send us.</li>
            </ul>
            <p>We don't sell your data and we don't use it for advertising.</p>

            <h2>4. Cookies &amp; local storage</h2>
            <ul class="info-list">
              <li><strong>Session cookie</strong> — identifies your signed-in session;
                essential for the Site to work.</li>
              <li><strong>Cart (localStorage)</strong> — your selected artworks are
                stored in your own browser until you check out. It never leaves your
                device and is cleared once an order is placed.</li>
            </ul>

            <h2>5. Sharing</h2>
            <p>
              Your information is used only to operate the Site. Nothing is shared
              with advertisers or data brokers. In this demo, no third-party payment
              processor is involved either.
            </p>

            <h2>6. Retention</h2>
            <p>
              Account and order records are kept while your account is active. When
              you delete an uploaded artwork, its image file is removed from our
              storage too. Orders are retained as purchase history.
            </p>

            <h2>7. Your rights</h2>
            <ul class="info-list">
              <li><strong>Access &amp; correction</strong> — ask us for a copy of your
                data or fix anything inaccurate.</li>
              <li><strong>Deletion</strong> — ask us to delete your account; we'll
                remove your personal details (artist listings go with them).</li>
              <li><strong>Withdrawal</strong> — you can stop using the Site at any
                time, and clear your browser cart and cookies whenever you like.</li>
            </ul>

            <h2>8. Children's privacy</h2>
            <p>
              The Site is intended for people aged 13 and over. We ask for a date of
              birth at registration to enforce this, and don't knowingly collect data
              from younger children.
            </p>

            <h2>9. Security</h2>
            <ul class="info-list">
              <li>Passwords are hashed with PHP's modern password hashing.</li>
              <li>Forms are protected against CSRF attacks.</li>
              <li>Uploaded images are verified by real file content, not just their
                file name.</li>
            </ul>

            <h2>10. Changes to this policy</h2>
            <p>
              Updates will appear on this page with a new “last updated” date.
            </p>

            <h2>11. Contact</h2>
            <p>
              Privacy questions? Reach us through the
              <a href="index.php#contact">contact form</a> or the
              <a href="support.php">Support &amp; Help</a> page.
            </p>
          </div>

        </div>

      </div>
    </section>

<?php include "includes/footer.php"; ?>