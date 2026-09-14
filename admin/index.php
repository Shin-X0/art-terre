<?php
/* ============================================================
   ART TERRE — Admin hub (admin only)
   Quick stats + links to every management page.
   ============================================================ */
require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";

$currentUser = current_user();
if (!$currentUser || !is_admin($currentUser)) {
  http_response_code(403);
  $pageTitle = "Access Denied — Art Terre Creations";
  $page = "checkout";
  include __DIR__ . "/../includes/header.php";
  echo '<div class="container"><div class="checkout-success"><h2>Access denied 🔒</h2>'
     . '<p>This page is for administrators only.</p>'
     . '<a href="../index.php" class="btn btn-outline">Back to Home</a></div></div>';
  include __DIR__ . "/../includes/footer.php";
  exit;
}

$stats = [
  "orders"    => (int) $db->query("SELECT COUNT(*) FROM orders")->fetch_row()[0],
  "pending"   => (int) $db->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetch_row()[0],
  "unread"    => (int) $db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetch_row()[0],
  "users"     => (int) $db->query("SELECT COUNT(*) FROM users")->fetch_row()[0],
  "artists"   => (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'artist'")->fetch_row()[0],
  "artworks"  => (int) $db->query("SELECT COUNT(*) FROM artworks")->fetch_row()[0],
];

$pageTitle = "Admin — Art Terre Creations";
$page = "checkout";
include __DIR__ . "/../includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Admin</h1>
        <p class="page-hero-sub reveal">Everything that keeps Art Terre running, in one place.</p>
      </div>
    </section>

    <!-- ======================= ADMIN ======================= -->
    <section class="section" id="admin">
      <div class="container">

        <div class="admin-stats reveal">
          <div class="admin-stat"><span class="admin-stat-num"><?php echo $stats["orders"]; ?></span><span>Orders</span></div>
          <div class="admin-stat"><span class="admin-stat-num"><?php echo $stats["pending"]; ?></span><span>Pending orders</span></div>
          <div class="admin-stat<?php echo $stats["unread"] > 0 ? " admin-stat--alert" : ""; ?>"><span class="admin-stat-num"><?php echo $stats["unread"]; ?></span><span>Unread messages</span></div>
          <div class="admin-stat"><span class="admin-stat-num"><?php echo $stats["users"]; ?></span><span>Users</span></div>
          <div class="admin-stat"><span class="admin-stat-num"><?php echo $stats["artists"]; ?></span><span>Artists</span></div>
          <div class="admin-stat"><span class="admin-stat-num"><?php echo $stats["artworks"]; ?></span><span>Artworks</span></div>
        </div>

        <div class="admin-links reveal">
          <a class="admin-link-card" href="messages.php">
            <strong>✉️ Messages</strong>
            <small>Read and manage contact-form messages<?php echo $stats["unread"] > 0 ? " ({$stats["unread"]} unread)" : ""; ?></small>
          </a>
          <a class="admin-link-card" href="orders.php">
            <strong>📦 Orders</strong>
            <small>View orders and update their status</small>
          </a>
          <a class="admin-link-card" href="users.php">
            <strong>👥 Users</strong>
            <small>Change roles (collector / artist / admin), remove users</small>
          </a>
        </div>

      </div>
    </section>

<?php include __DIR__ . "/../includes/footer.php"; ?>