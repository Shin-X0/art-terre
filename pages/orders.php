<?php
/* ============================================================
   ART TERRE — My Orders page
   Signed-in customers track their orders and see live status
   updates from the admin (pending → processing → shipped …).
   ============================================================ */
require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";

$user = current_user();
if (!$user) {
  header("Location: login.php");
  exit;
}

$STATUS_LABELS = [
  "pending"    => "Pending",
  "processing" => "Processing",
  "shipped"    => "Shipped",
  "delivered"  => "Delivered",
  "cancelled"  => "Cancelled",
];

$orders = [];
$stmt = $db->prepare(
  "SELECT o.*, (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
   FROM orders o WHERE o.user_id = ? ORDER BY o.created_at DESC, o.id DESC"
);
$stmt->bind_param("i", $user["id"]);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$pageTitle = "My Orders — Art Terre Creations";
$page = "checkout";
include __DIR__ . "/../includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">My Orders</h1>
        <p class="page-hero-sub reveal">Every artwork you've ordered, with its live status.</p>
      </div>
    </section>

    <!-- ======================= ORDERS ======================= -->
    <section class="checkout-section section" id="orders">
      <div class="container">

        <?php if (!$orders): ?>
          <div class="checkout-success reveal">
            <h2>No orders yet</h2>
            <p>When you place an order it will appear here with its status.</p>
            <a href="artworks.php" class="btn btn-accent">Browse Artworks</a>
          </div>

        <?php else: ?>
          <div class="orders-list">
            <?php foreach ($orders as $o): ?>
              <article class="order-card reveal">
                <div class="order-card-head">
                  <div>
                    <strong class="order-no"><?php echo htmlspecialchars($o["order_no"], ENT_QUOTES, "UTF-8"); ?></strong>
                    <span class="order-date"><?php echo htmlspecialchars(date("M j, Y", strtotime($o["created_at"])), ENT_QUOTES, "UTF-8"); ?></span>
                  </div>
                  <span class="status-badge st-<?php echo htmlspecialchars($o["status"], ENT_QUOTES, "UTF-8"); ?>">
                    <?php echo htmlspecialchars($STATUS_LABELS[$o["status"]] ?? ucfirst($o["status"]), ENT_QUOTES, "UTF-8"); ?>
                  </span>
                </div>

                <p class="order-meta">
                  <?php echo (int) $o["item_count"]; ?> item<?php echo (int) $o["item_count"] === 1 ? "" : "s"; ?>
                  · <?php echo $o["payment_method"] === "cod" ? "💵 Cash on Delivery" : "💳 Card"; ?>
                  · <strong>$<?php echo number_format((float) $o["total"], 2); ?></strong>
                </p>

                <a class="order-view-link" href="order-pending.php?no=<?php echo urlencode($o["order_no"]); ?>">View order details →</a>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    </section>

<?php include __DIR__ . "/../includes/footer.php"; ?>