<?php
/* ============================================================
   ART TERRE — My Sales (seller/artist only)
   Orders that contain artworks owned by the signed-in seller.
   ONLY this seller (or an admin via admin/orders.php) can update
   these orders' statuses — buyers stay view-only in pages/orders.php.
   Sellers are sell-only and can never buy (see checkout.php).
   ============================================================ */
require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";

$user = current_user();
if (!$user) {
  header("Location: login.php");
  exit;
}

/* Admins manage everything in admin/orders.php — sellers use this page. */
if (is_admin($user)) {
  header("Location: ../admin/orders.php");
  exit;
}

if (!is_artist($user)) {
  http_response_code(403);
  $pageTitle = "Access Denied — Art Terre Creations";
  $page = "checkout";
  include __DIR__ . "/../includes/header.php";
  echo '<div class="container"><div class="checkout-success"><h2>Sellers only</h2>'
     . '<p>This page is for artist accounts.</p>'
     . '<a href="orders.php" class="btn btn-outline">My Orders</a></div></div>';
  include __DIR__ . "/../includes/footer.php";
  exit;
}

$STATUS_LABELS = [
  "pending" => "Pending", "processing" => "Processing", "shipped" => "Shipped",
  "delivered" => "Delivered", "cancelled" => "Cancelled",
];

/* POST: seller updates status of THEIR order only. */
if ($_SERVER["REQUEST_METHOD"] === "POST" && csrf_check() && ($_POST["action"] ?? "") === "update_status") {
  $orderId = (int) ($_POST["order_id"] ?? 0);
  $newStatus = $_POST["status"] ?? "";
  if (isset($STATUS_LABELS[$newStatus]) && can_update_order_status($user, $orderId)) {
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $orderId);
    $stmt->execute();
    $stmt->close();
    header("Location: sales.php?updated=" . $orderId);
    exit;
  }
  header("Location: sales.php?denied=1");
  exit;
}

/* Orders containing this seller's artworks. */
$orders = [];
if (order_items_have_ownership()) {
  $stmt = $db->prepare(
    "SELECT DISTINCT o.*,
       (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS item_count,
       (SELECT GROUP_CONCAT(CONCAT(oi.title, ' x ', oi.qty) SEPARATOR ', ')
        FROM order_items oi WHERE oi.order_id = o.id AND oi.artist_id = ?) AS my_items
     FROM orders o
     JOIN order_items mine ON mine.order_id = o.id AND mine.artist_id = ?
     ORDER BY o.created_at DESC, o.id DESC"
  );
  $stmt->bind_param("ii", $user["id"], $user["id"]);
  $stmt->execute();
  $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
}

$pageTitle = "My Sales — Art Terre Creations";
$page = "checkout";
include __DIR__ . "/../includes/header.php";
?>
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">My Sales</h1>
        <p class="page-hero-sub reveal">Orders for your artworks — only you (the seller) can update their status.</p>
      </div>
    </section>

    <section class="checkout-section section" id="sales">
      <div class="container">
        <?php if (isset($_GET["updated"])): ?>
          <p class="upload-flash" role="status">Order #<?php echo (int) $_GET["updated"]; ?> status updated.</p>
        <?php elseif (isset($_GET["denied"])): ?>
          <p class="auth-error" role="alert">You can only update orders containing your own artworks.</p>
        <?php endif; ?>
        <?php if (!$orders): ?>
          <div class="checkout-success reveal">
            <h2>No sales yet</h2>
            <p>When a collector buys one of your artworks, the order will appear here for you to update.</p>
            <a href="upload-artwork.php" class="btn btn-accent">Sell Artwork</a>
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
                  <?php echo htmlspecialchars($o["name"], ENT_QUOTES, "UTF-8"); ?>
                  · <?php echo (int) $o["item_count"]; ?> item<?php echo (int) $o["item_count"] === 1 ? "" : "s"; ?>
                  · <?php echo $o["payment_method"] === "cod" ? "COD" : "Card"; ?>
                  · <strong>$<?php echo number_format((float) $o["total"], 2); ?></strong>
                </p>
                <?php if (!empty($o["my_items"])): ?>
                  <p class="order-meta">Your piece(s): <?php echo htmlspecialchars($o["my_items"], ENT_QUOTES, "UTF-8"); ?></p>
                <?php endif; ?>
                <form class="order-status-form" method="post" action="sales.php">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="action" value="update_status" />
                  <input type="hidden" name="order_id" value="<?php echo (int) $o["id"]; ?>" />
                  <label class="sr-only" for="status-<?php echo (int) $o["id"]; ?>">Status</label>
                  <select id="status-<?php echo (int) $o["id"]; ?>" name="status">
                    <?php foreach ($STATUS_LABELS as $key => $label): ?>
                      <option value="<?php echo $key; ?>" <?php echo $o["status"] === $key ? "selected" : ""; ?>><?php echo $label; ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit" class="btn btn-accent order-status-save">Update Status</button>
                  <a href="order-pending.php?no=<?php echo urlencode($o["order_no"]); ?>" class="order-view-link">View</a>
                </form>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

<?php include __DIR__ . "/../includes/footer.php"; ?>
