<?php
/* ============================================================
   ART TERRE — Shopping Cart page
   Cart items live in localStorage (see js/script.js §6).
   ============================================================ */
$pageTitle = "Your Cart — Art Terre Creations";
$page = "cart";
include "includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Your Cart</h1>
        <p class="page-hero-sub reveal">
          Review your selected artworks before checkout — every piece is
          affordable, original, and just a click away.
        </p>
      </div>
    </section>

    <!-- ======================= CART ======================= -->
    <section class="cart-section section" id="cart">
      <div class="container cart-layout">

        <!-- Items (rendered by js/script.js from localStorage) -->
        <div class="reveal">
          <div id="cart-items"></div>

          <div class="cart-empty" id="cart-empty" hidden>
            <p>Your cart is empty — go find something beautiful.</p>
            <a href="artworks.php" class="btn btn-outline">Browse Artworks</a>
          </div>
        </div>

        <!-- Summary -->
        <aside class="cart-summary reveal">
          <h2 class="cart-summary-title">Order Summary</h2>
          <div class="cart-summary-line">
            <span>Subtotal</span>
            <span id="cart-subtotal">$0.00</span>
          </div>
          <div class="cart-summary-line muted">
            <span>Delivery</span>
            <span>Free</span>
          </div>
          <div class="cart-summary-line total">
            <span>Total</span>
            <span id="cart-total">$0.00</span>
          </div>

          <a href="checkout.php" class="btn btn-accent cart-checkout-btn" id="to-checkout">Proceed to Checkout</a>
          <a href="artworks.php" class="cart-continue">Continue shopping</a>
        </aside>

      </div>
    </section>

<?php include "includes/footer.php"; ?>