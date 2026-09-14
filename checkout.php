<?php
/* ============================================================
   ART TERRE — Checkout page
   Payment by Card or Cash on Delivery. Orders are stored in
   the orders / order_items tables, then the buyer is
   redirected to order-pending.php. Cart items arrive as JSON
   in "cart_json" (filled by js/script.js from localStorage).
   ============================================================ */
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/auth.php";
$currentUser = current_user();

/* Checkout requires an account — guests are sent to sign in first */
if (!$currentUser) {
  header("Location: login.php");
  exit;
}

$errors = [];
$old = ["name" => "", "email" => "", "address" => "", "city" => "", "zip" => "", "payment" => "cod"];

/* Signed-in users get their billing details pre-filled */
if ($currentUser) {
  $old["name"]  = $currentUser["name"];
  $old["email"] = $currentUser["email"];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!csrf_check()) {
    $errors["form"] = "Your session expired — please try again.";
  } else {
    $name    = trim($_POST["name"] ?? "");
    $email   = strtolower(trim($_POST["email"] ?? ""));
    $address = trim($_POST["address"] ?? "");
    $city    = trim($_POST["city"] ?? "");
    $zip     = trim($_POST["zip"] ?? "");
    $payment = $_POST["payment"] ?? "cod";
    $old["name"]    = $name;
    $old["email"]   = $email;
    $old["address"] = $address;
    $old["city"]    = $city;
    $old["zip"]     = $zip;
    $old["payment"] = $payment;

    if (mb_strlen($name) < 2) {
      $errors["name"] = "Please enter your full name.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors["email"] = "Enter a valid email address.";
    }
    if (mb_strlen($address) < 5) {
      $errors["address"] = "Please enter your delivery address.";
    }

    /* Payment method + card details (card fields only when paying by card) */
    if (!in_array($payment, ["card", "cod"], true)) {
      $payment = "cod";
    }
    if ($payment === "card") {
      $cardNumber = preg_replace("/\D/", "", $_POST["card"] ?? "");
      $expiry     = trim($_POST["expiry"] ?? "");
      $cvv        = trim($_POST["cvv"] ?? "");

      if (!preg_match("/^\d{13,19}$/", $cardNumber)) {
        $errors["card"] = "Enter a valid card number (13–19 digits).";
      }
      if (!preg_match("/^(0[1-9]|1[0-2])\/\d{2}$/", $expiry)) {
        $errors["expiry"] = "Expiry must be in MM/YY format.";
      }
      if (!preg_match("/^\d{3,4}$/", $cvv)) {
        $errors["cvv"] = "CVV must be 3 or 4 digits.";
      }
      /* Note: card details are validated but NEVER stored — demo only. */
    }

    /* Cart items (JSON snapshot from the browser cart) */
    $cartItems = [];
    $cartRaw = json_decode($_POST["cart_json"] ?? "[]", true);
    if (is_array($cartRaw)) {
      foreach ($cartRaw as $row) {
        if (!is_array($row)) continue;
        $t = trim((string) ($row["title"] ?? ""));
        $a = trim((string) ($row["artist"] ?? ""));
        $p = (float) ($row["price"] ?? 0);
        $q = (int) ($row["qty"] ?? 0);
        if ($t === "" || $p <= 0 || $p > 99999.99 || $q < 1 || $q > 99) continue;
        $cartItems[] = [
          "title"  => mb_substr($t, 0, 150),
          "artist" => $a !== "" ? mb_substr($a, 0, 100) : null,
          "price"  => round($p, 2),
          "qty"    => $q,
        ];
      }
    }
    if (!$cartItems) {
      $errors["form"] = "Your cart is empty — add an artwork first.";
    }

    if (!$errors) {
      $total = 0.0;
      foreach ($cartItems as $row) {
        $total += $row["price"] * $row["qty"];
      }
      $total   = round($total, 2);
      $orderNo = "AT-" . strtoupper(bin2hex(random_bytes(3)));
      $userId  = $currentUser["id"] ?? null;
      $cityVal = $city !== "" ? $city : null;
      $zipVal  = $zip !== "" ? $zip : null;

      $db->begin_transaction();
      try {
        $stmt = $db->prepare(
          "INSERT INTO orders
             (order_no, user_id, name, email, address, city, zip, payment_method, status, total)
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)"
        );
        $stmt->bind_param("sissssssd", $orderNo, $userId, $name, $email, $address, $cityVal, $zipVal, $payment, $total);
        $stmt->execute();
        $orderId = (int) $stmt->insert_id;
        $stmt->close();

        $stmt = $db->prepare(
          "INSERT INTO order_items (order_id, title, artist, price, qty) VALUES (?, ?, ?, ?, ?)"
        );
        foreach ($cartItems as $row) {
          $stmt->bind_param("issdi", $orderId, $row["title"], $row["artist"], $row["price"], $row["qty"]);
          $stmt->execute();
        }
        $stmt->close();

        $db->commit();
      } catch (mysqli_sql_exception $e) {
        $db->rollback();
        $errors["form"] = "Could not place the order — please try again.";
      }

      if (!$errors) {
        /* Post/Redirect/Get → pending page (the cart is cleared there) */
        header("Location: order-pending.php?no=" . urlencode($orderNo));
        exit;
      }
    }
  }
}

$pageTitle = "Checkout — Art Terre Creations";
$page = "checkout";
include "includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Checkout</h1>
        <p class="page-hero-sub reveal">
          A few details and your art is on its way — securely and sustainably.
        </p>
      </div>
    </section>

    <!-- ======================= CHECKOUT ======================= -->
    <section class="checkout-section section" id="checkout">
      <div class="container checkout-layout" id="checkout-layout">

        <!-- Billing + payment form -->
        <form class="checkout-form reveal" id="checkout-form" method="post" action="checkout.php" novalidate>
          <?php echo csrf_field(); ?>
          <input type="hidden" name="cart_json" id="cart-json" value="[]" />

          <?php if (isset($errors["form"])): ?>
            <p class="auth-error"><?php echo htmlspecialchars($errors["form"], ENT_QUOTES, "UTF-8"); ?></p>
          <?php endif; ?>

          <h2>Billing Details</h2>

          <div class="field">
            <label for="co-name">Full Name</label>
            <input type="text" id="co-name" name="name" value="<?php echo htmlspecialchars($old["name"], ENT_QUOTES, "UTF-8"); ?>" autocomplete="name" required />
            <?php if (isset($errors["name"])): ?>
              <p class="field-error"><?php echo htmlspecialchars($errors["name"], ENT_QUOTES, "UTF-8"); ?></p>
            <?php endif; ?>
          </div>

          <div class="field">
            <label for="co-email">Email Address</label>
            <input type="email" id="co-email" name="email" value="<?php echo htmlspecialchars($old["email"], ENT_QUOTES, "UTF-8"); ?>" autocomplete="email" required />
            <?php if (isset($errors["email"])): ?>
              <p class="field-error"><?php echo htmlspecialchars($errors["email"], ENT_QUOTES, "UTF-8"); ?></p>
            <?php endif; ?>
          </div>

          <div class="field">
            <label for="co-address">Delivery Address</label>
            <input type="text" id="co-address" name="address" value="<?php echo htmlspecialchars($old["address"], ENT_QUOTES, "UTF-8"); ?>" autocomplete="street-address" required />
            <?php if (isset($errors["address"])): ?>
              <p class="field-error"><?php echo htmlspecialchars($errors["address"], ENT_QUOTES, "UTF-8"); ?></p>
            <?php endif; ?>
          </div>

          <div class="field-row">
            <div class="field">
              <label for="co-city">City</label>
              <input type="text" id="co-city" name="city" value="<?php echo htmlspecialchars($old["city"], ENT_QUOTES, "UTF-8"); ?>" autocomplete="address-level2" />
            </div>
            <div class="field">
              <label for="co-zip">ZIP / Postal Code</label>
              <input type="text" id="co-zip" name="zip" value="<?php echo htmlspecialchars($old["zip"], ENT_QUOTES, "UTF-8"); ?>" autocomplete="postal-code" />
            </div>
          </div>

          <h2>Payment Method</h2>

          <fieldset class="field role-field">
            <legend>How would you like to pay?</legend>
            <div class="role-choices">
              <label class="role-choice">
                <input type="radio" name="payment" value="card" data-payment-option <?php echo $old["payment"] === "card" ? "checked" : ""; ?> />
                <span class="role-card">
                  <strong>💳 Card</strong>
                  <small>Pay now with your credit or debit card.</small>
                </span>
              </label>
              <label class="role-choice">
                <input type="radio" name="payment" value="cod" data-payment-option <?php echo $old["payment"] !== "card" ? "checked" : ""; ?> />
                <span class="role-card">
                  <strong>💵 Cash on Delivery</strong>
                  <small>Pay in cash when your artwork arrives.</small>
                </span>
              </label>
            </div>
          </fieldset>

          <!-- Card fields — only required when "Card" is selected -->
          <div id="card-fields" <?php echo $old["payment"] === "card" ? "" : "hidden"; ?>>
            <div class="field">
              <label for="co-card">Card Number</label>
              <input type="text" id="co-card" name="card" inputmode="numeric" placeholder="1234 5678 9012 3456" autocomplete="cc-number" />
              <?php if (isset($errors["card"])): ?>
                <p class="field-error"><?php echo htmlspecialchars($errors["card"], ENT_QUOTES, "UTF-8"); ?></p>
              <?php endif; ?>
            </div>

            <div class="field-row">
              <div class="field">
                <label for="co-expiry">Expiry (MM/YY)</label>
                <input type="text" id="co-expiry" name="expiry" inputmode="numeric" placeholder="08/28" autocomplete="cc-exp" />
                <?php if (isset($errors["expiry"])): ?>
                  <p class="field-error"><?php echo htmlspecialchars($errors["expiry"], ENT_QUOTES, "UTF-8"); ?></p>
                <?php endif; ?>
              </div>
              <div class="field">
                <label for="co-cvv">CVV</label>
                <input type="text" id="co-cvv" name="cvv" inputmode="numeric" placeholder="123" autocomplete="cc-csc" />
                <?php if (isset($errors["cvv"])): ?>
                  <p class="field-error"><?php echo htmlspecialchars($errors["cvv"], ENT_QUOTES, "UTF-8"); ?></p>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <p class="checkout-note">This is a demo checkout — card details are never stored, and no real payment is processed.</p>

          <button type="submit" class="btn btn-accent checkout-submit">Place Order</button>
          <p class="form-msg" id="checkout-msg" role="status" aria-live="polite"></p>
        </form>

        <!-- Order summary -->
        <aside class="cart-summary reveal">
          <h2 class="cart-summary-title">Order Summary</h2>
          <div class="checkout-items" id="checkout-items"></div>
          <div class="cart-summary-line muted">
            <span>Delivery</span>
            <span>Free</span>
          </div>
          <div class="cart-summary-line total">
            <span>Total</span>
            <span id="checkout-total">$0.00</span>
          </div>
        </aside>

      </div>
    </section>

<?php include "includes/footer.php"; ?>