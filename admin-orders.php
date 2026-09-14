<?php
/* ============================================================
   ART TERRE — Manage Orders (admin only)
   Lists every order and lets admins update the status:
   pending → processing → shipped → delivered (or cancelled).
   Guarded by ADMIN_EMAILS in includes/config.php.
   ============================================================ */
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/auth.php";

$currentUser = current_user();
if (!$currentUser || !is_admin($currentUser)) {
  http_response_code(403);
  $pageTitle = "Access Denied — Art Terre Creations";
  $page = "checkout";
  include "includes/header.php";
  echo '<div class="container"><div class="checkout-success"><h2>Access denied 🔒</h2>'
     . '<p>This page is for administrators only.</p>'
     . '<a href="index.php" class="btn btn-outline">Back to Home</a></div></div>';
  include "includes/footer.php";
  exit;
}

$STATUS_LABELS = [
  "pending"    => "Pending",
  "processing" => "Processing",
  "shipped"    => "Shipped",
  "delivered"  => "Delivered",
  "cancelled"  => "Cancelled",
];

/* ---------- POST: update one order's status ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && csrf_check() && ($_POST["action"] ?? "") === "update_status") {
  $orderId = (int) ($_POST["order_id"] ?? 0);
  $newStatus = $_POST["status"] ?? "";

  if (isset($STATUS_LABELS[$newStatus])) {
    $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $orderId);
    $stmt->execute();
    $stmt->close();
  }

  header("Location: admin-orders.php?updated=" . (int) $orderId);
  exit;
}

$orders = $db->query(
  "SELECT o.*, (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
   FROM orders o ORDER BY o.created_at DESC, o.id DESC"
)->fetch_all(MYSQLI_ASSOC);

$pageTitle = "Manage Orders — Art Terre Creations";
$page = "checkout";
include "includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Manage Orders</h1>
        <p class="page-hero-sub reveal">Update the status of every order — customers see the change instantly.</p>
      </div>
    </section>

    <!-- ======================= ORDERS ======================= -->
    <section class="checkout-section section" id="admin-orders">
      <div class="container">

        <?php if (isset($_GET["updated"])): ?>
          <p class="upload-flash" role="status">Order #<?php echo (int) $_GET["updated"]; ?> status updated.</p>
        <?php endif; ?>

        <?php if (!$orders): ?>
          <div class="checkout-success reveal">
            <h2>No orders yet</h2>
            <p>Orders placed at checkout will show up here.</p>
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
                  (<?php echo htmlspecialchars($o["email"], ENT_QUOTES, "UTF-8"); ?>)
                  · <?php echo (int) $o["item_count"]; ?> item<?php echo (int) $o["item_count"] === 1 ? "" : "s"; ?>
                  · <?php echo $o["payment_method"] === "cod" ? "💵 COD" : "💳 Card"; ?>
                  · <strong>$<?php echo number_format((float) $o["total"], 2); ?></strong>
                </p>

                <form class="order-status-form" method="post" action="admin-orders.php">
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
                  <a href="order-pending.php?no=<?php echo urlencode($o["order_no"]); ?>" class="order-view-link">View →</a>
                </form>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    </section>

<?php include "includes/footer.php"; ?>