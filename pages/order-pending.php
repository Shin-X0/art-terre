<?php
/* ============================================================
   ART TERRE — Order page (view-only for buyers, status set by seller)
   Shown right after checkout. Displays the order status
   (starts as "Pending"), payment instructions for the chosen
   method (Card or Cash on Delivery), and clears the cart.
   Sellers see a status UPDATE form ONLY for orders containing
   THEIR artworks (checked via can_update_order_status());
   everyone else gets the badge, read-only.
   ============================================================ */
require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";

$currentUser = current_user();

$order = null;
$items = [];
$orderNo = trim($_GET["no"] ?? "");

if ($orderNo !== "") {
  $stmt = $db->prepare("SELECT * FROM orders WHERE order_no = ? LIMIT 1");
  $stmt->bind_param("s", $orderNo);
  $stmt->execute();
  $order = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

/* Seller updates status of THEIR order from this page too. */
$statusMsg = "";
if ($order && $_SERVER["REQUEST_METHOD"] === "POST" && csrf_check() && ($_POST["action"] ?? "") === "update_status") {
  $newStatus = $_POST["status"] ?? "";
  $labels = ["pending" => 1, "processing" => 1, "shipped" => 1, "delivered" => 1, "cancelled" => 1];
  if (isset($labels[$newStatus]) && can_update_order_status($currentUser, (int) $order["id"])) {
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $order["id"]);
    $stmt->execute();
    $stmt->close();
    $order["status"] = $newStatus;
    $statusMsg = "updated";
  } else {
    $statusMsg = "denied";
  }
}

if ($order) {
  $stmt = $db->prepare("SELECT title, artist, price, qty FROM order_items WHERE order_id = ? ORDER BY id ASC");
  $stmt->bind_param("i", $order["id"]);
  $stmt->execute();
  $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
}

/* Can the signed-in user change THIS order's status? Seller-owner or admin only. */
$canUpdate = $order ? can_update_order_status($currentUser, (int) $order["id"]) : false;

$STATUS_LABELS = [
  "pending"    => "Pending",
  "processing" => "Processing",
  "shipped"    => "Shipped",
  "delivered"  => "Delivered",
  "cancelled"  => "Cancelled",
];
$status      = $order["status"] ?? "pending";
$statusLabel = $STATUS_LABELS[$status] ?? ucfirst($status);

$pageTitle = "Order " . ($order["order_no"] ?? "") . " — Art Terre Creations";
$page = "checkout";
include __DIR__ . "/../includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Your Order</h1>
        <p class="page-hero-sub reveal">Track and review your freshly placed order.</p>
      </div>
    </section>

    <!-- ======================= ORDER ======================= -->
    <section class="checkout-section section" id="order">
      <div class="container">

        <?php if (!$order): ?>
          <div class="checkout-success reveal">
            <h2>Order not found</h2>
            <p>We couldn't find an order with that number.</p>
            <a href="artworks.php" class="btn btn-outline">Browse Artworks</a>
          </div>

        <?php else: ?>
          <div class="checkout-success reveal">
            <h2>Thank you, <?php echo htmlspecialchars(explode(" ", trim($order["name"]))[0], ENT_QUOTES, "UTF-8"); ?>! 🎨</h2>

            <p class="order-status-line">
              Order <strong><?php echo htmlspecialchars($order["order_no"], ENT_QUOTES, "UTF-8"); ?></strong>
              · <span class="status-badge st-<?php echo htmlspecialchars($status, ENT_QUOTES, "UTF-8"); ?>"><?php echo htmlspecialchars($statusLabel, ENT_QUOTES, "UTF-8"); ?></span>
            </p>
            <?php if ($statusMsg === "updated"): ?>
              <p class="upload-flash" role="status">Status updated.</p>
            <?php elseif ($statusMsg === "denied"): ?>
              <p class="auth-error" role="alert">Only the artwork's seller can update this order's status.</p>
            <?php endif; ?>
            <?php if ($canUpdate): ?>
              <form class="order-status-form" method="post" action="order-pending.php?no=<?php echo urlencode($order["order_no"]); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="update_status" />
                <label class="sr-only" for="order-status">Status</label>
                <select id="order-status" name="status">
                  <?php foreach ($STATUS_LABELS as $key => $label): ?>
                    <option value="<?php echo $key; ?>" <?php echo $status === $key ? "selected" : ""; ?>><?php echo $label; ?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-accent order-status-save">Update Status</button>
              </form>
            <?php else: ?>
              <p class="order-note">Status is set by the artwork's seller — track it here (view-only).</p>
            <?php endif; ?>

            <?php if ($order["payment_method"] === "cod"): ?>
              <p class="payment-note">
                💵 <strong>Cash on Delivery</strong> — please have
                <strong>$<?php echo number_format((float) $order["total"], 2); ?></strong>
                ready in cash when your artwork arrives at
                <?php echo htmlspecialchars($order["address"], ENT_QUOTES, "UTF-8"); ?>.
              </p>
            <?php else: ?>
              <p class="payment-note">
                💳 <strong>Card payment</strong> — your payment is being verified
                (demo: no real charge was made). The order stays <strong>Pending</strong>
                until the payment is confirmed.
              </p>
            <?php endif; ?>

            <p class="order-note">
              You'll receive updates at <strong><?php echo htmlspecialchars($order["email"], ENT_QUOTES, "UTF-8"); ?></strong>
              as the status moves from <em>Pending</em> to <em>Processing</em>, <em>Shipped</em>, and <em>Delivered</em>.
            </p>

            <div class="order-lines">
              <?php foreach ($items as $item): ?>
                <div class="checkout-line">
                  <span><?php echo htmlspecialchars($item["title"], ENT_QUOTES, "UTF-8"); ?> × <?php echo (int) $item["qty"]; ?></span>
                  <span>$<?php echo number_format((float) $item["price"] * (int) $item["qty"], 2); ?></span>
                </div>
              <?php endforeach; ?>
              <div class="checkout-line total">
                <span>Total</span>
                <span>$<?php echo number_format((float) $order["total"], 2); ?></span>
              </div>
            </div>

            <div class="order-actions">
              <?php if ($currentUser && is_artist($currentUser) && !is_admin($currentUser)): ?>
                <a href="sales.php" class="btn btn-accent">My Sales</a>
              <?php else: ?>
                <a href="orders.php" class="btn btn-accent">My Orders</a>
              <?php endif; ?>
              <a href="artworks.php" class="btn btn-outline">Keep Exploring Art</a>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </section>

<?php include __DIR__ . "/../includes/footer.php"; ?>

<script>
  /* The order is stored — empty the localStorage cart */
  localStorage.removeItem("art-terre-cart");
  const cc = document.getElementById("cart-count");
  if (cc) cc.textContent = "0";
</script>