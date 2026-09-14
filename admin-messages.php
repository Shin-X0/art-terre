<?php
/* ============================================================
   ART TERRE — Messages inbox (admin only)
   Contact-form messages: mark as read, delete.
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

/* ---------- POST actions: mark read / delete ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && csrf_check()) {
  $action = $_POST["action"] ?? "";
  $msgId  = (int) ($_POST["message_id"] ?? 0);

  if ($action === "mark_read" && $msgId > 0) {
    $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $msgId);
    $stmt->execute();
    $stmt->close();
  }

  if ($action === "delete" && $msgId > 0) {
    $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $msgId);
    $stmt->execute();
    $stmt->close();
  }

  header("Location: admin-messages.php");
  exit;
}

$messages = $db->query(
  "SELECT * FROM contact_messages ORDER BY is_read ASC, created_at DESC, id DESC"
)->fetch_all(MYSQLI_ASSOC);

$unread = 0;
foreach ($messages as $m) {
  if (!$m["is_read"]) $unread++;
}

$pageTitle = "Messages — Art Terre Creations";
$page = "checkout";
include "includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Messages</h1>
        <p class="page-hero-sub reveal">
          Contact-form inbox — <?php echo count($messages); ?> message(s), <?php echo $unread; ?> unread.
        </p>
      </div>
    </section>

    <!-- ======================= INBOX ======================= -->
    <section class="section" id="messages">
      <div class="container">

        <?php if (!$messages): ?>
          <div class="checkout-success reveal">
            <h2>No messages yet</h2>
            <p>Messages sent through the contact form will appear here.</p>
          </div>

        <?php else: ?>
          <div class="orders-list">
            <?php foreach ($messages as $m): ?>
              <article class="order-card reveal<?php echo $m["is_read"] ? "" : " message-unread"; ?>">
                <div class="order-card-head">
                  <div>
                    <strong class="order-no"><?php echo htmlspecialchars($m["name"], ENT_QUOTES, "UTF-8"); ?></strong>
                    <span class="order-date">
                      <a href="mailto:<?php echo htmlspecialchars($m["email"], ENT_QUOTES, "UTF-8"); ?>"><?php echo htmlspecialchars($m["email"], ENT_QUOTES, "UTF-8"); ?></a>
                      · <?php echo htmlspecialchars(date("M j, Y g:i a", strtotime($m["created_at"])), ENT_QUOTES, "UTF-8"); ?>
                    </span>
                  </div>
                  <?php if (!$m["is_read"]): ?>
                    <span class="status-badge st-pending">Unread</span>
                  <?php endif; ?>
                </div>

                <p class="message-text"><?php echo nl2br(htmlspecialchars($m["message"], ENT_QUOTES, "UTF-8")); ?></p>

                <div class="message-actions">
                  <?php if (!$m["is_read"]): ?>
                    <form method="post" action="admin-messages.php">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="mark_read" />
                      <input type="hidden" name="message_id" value="<?php echo (int) $m["id"]; ?>" />
                      <button type="submit" class="btn btn-outline message-btn">Mark as read</button>
                    </form>
                  <?php endif; ?>
                  <form method="post" action="admin-messages.php" onsubmit="return confirm('Delete this message?');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="message_id" value="<?php echo (int) $m["id"]; ?>" />
                    <button type="submit" class="remove-btn message-btn">Delete</button>
                  </form>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    </section>

<?php include "includes/footer.php"; ?>