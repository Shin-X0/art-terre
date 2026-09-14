<?php
/* ============================================================
   ART TERRE — Messages inbox (admin only)
   Every Contact-Us message lands here in the admin account.
   The admin can reply directly on the site — the reply is saved
   and shown to the signed-in sender in their "My Messages" page.
   For guests the admin can still answer by email.
   On the site the admin can:
     - Write a reply in the reply box (saves reply/replied_at/replied_by)
     - Mark as Waiting / Responded (without a reply, just a status flip)
   The sender's email + linked account stay visible so the admin
   knows exactly who to contact.
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

/* ---------- POST actions: reply / responded-status / mark read / delete ---------- */
$flash = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && csrf_check()) {
  $action = $_POST["action"] ?? "";
  $msgId  = (int) ($_POST["message_id"] ?? 0);

  if ($action === "reply" && $msgId > 0) {
    $replyText = trim($_POST["reply_text"] ?? "");
    if ($replyText !== "") {
      $stmt = $db->prepare(
        "UPDATE contact_messages SET reply = ?, replied_at = NOW(), replied_by = ?, is_read = 1 WHERE id = ?"
      );
      $stmt->bind_param("sii", $replyText, $currentUser["id"], $msgId);
      $stmt->execute();
      $stmt->close();
      $flash = "reply_sent";
    }
  }

  if ($action === "mark_responded" && $msgId > 0) {
    $stmt = $db->prepare(
      "UPDATE contact_messages SET reply = '', replied_at = NOW(), replied_by = ?, is_read = 1 WHERE id = ?"
    );
    $stmt->bind_param("ii", $currentUser["id"], $msgId);
    $stmt->execute();
    $stmt->close();
    $flash = "responded";
  }

  if ($action === "mark_waiting" && $msgId > 0) {
    $stmt = $db->prepare(
      "UPDATE contact_messages SET reply = NULL, replied_at = NULL, replied_by = NULL WHERE id = ?"
    );
    $stmt->bind_param("i", $msgId);
    $stmt->execute();
    $stmt->close();
    $flash = "waiting";
  }

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

  header("Location: messages.php" . ($flash !== "" ? "?{$flash}=1" : ""));
  exit;
}

$messages = $db->query(
  "SELECT m.*,
          s.id AS sender_id, s.name AS sender_name, s.email AS sender_email,
          s.role AS sender_role, s.created_at AS sender_joined,
          r.name AS replier_name
   FROM contact_messages m
   LEFT JOIN users s ON s.id = m.user_id
   LEFT JOIN users r ON r.id = m.replied_by
   ORDER BY m.is_read ASC, m.created_at DESC, m.id DESC"
)->fetch_all(MYSQLI_ASSOC);

$unread = 0;
foreach ($messages as $m) {
  if (!$m["is_read"]) $unread++;
}

$pageTitle = "Messages — Art Terre Creations";
$page = "checkout";
include __DIR__ . "/../includes/header.php";
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

        <?php if (isset($_GET["responded"])): ?>
          <p class="upload-flash" role="status">Marked as Responded — the status has been updated.</p>

        <?php elseif (isset($_GET["reply_sent"])): ?>
          <p class="upload-flash" role="status">Reply sent! The user will see it in their "My Messages" page.</p>

        <?php elseif (isset($_GET["waiting"])): ?>
          <p class="upload-flash" role="status">Moved back to Waiting for reply.</p>
        <?php endif; ?>

        <?php if (!$messages): ?>
          <div class="checkout-success reveal">
            <h2>No messages yet</h2>
            <p>Messages sent through the contact form will appear here.</p>
          </div>

        <?php else: ?>
          <div class="orders-list">
            <?php foreach ($messages as $m): ?>
              <?php $responded = array_key_exists("reply", $m) && $m["reply"] !== null; ?>
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
                  <?php elseif ($responded): ?>
                    <span class="status-badge st-delivered">Responded</span>
                  <?php else: ?>
                    <span class="status-badge st-processing">Waiting for reply</span>
                  <?php endif; ?>
                </div>

                <p class="message-text"><?php echo nl2br(htmlspecialchars($m["message"], ENT_QUOTES, "UTF-8")); ?></p>

                <div class="sender-box">
                  <p class="sender-box-title">Sender</p>
                  <p class="order-meta">
                    Email: <a href="mailto:<?php echo htmlspecialchars($m["email"], ENT_QUOTES, "UTF-8"); ?>"><?php echo htmlspecialchars($m["email"], ENT_QUOTES, "UTF-8"); ?></a>
                  </p>
                  <?php if (!empty($m["sender_id"])): ?>
                    <p class="order-meta">
                      Account: <strong><?php echo htmlspecialchars($m["sender_name"] ?? $m["name"], ENT_QUOTES, "UTF-8"); ?></strong>
                      (<?php echo htmlspecialchars($m["sender_email"] ?? $m["email"], ENT_QUOTES, "UTF-8"); ?>)
                      · <?php echo htmlspecialchars(ucfirst($m["sender_role"] ?? "collector"), ENT_QUOTES, "UTF-8"); ?>
                      · joined <?php echo htmlspecialchars(date("M j, Y", strtotime($m["sender_joined"])), ENT_QUOTES, "UTF-8"); ?>
                      · user #<?php echo (int) $m["sender_id"]; ?>
                    </p>
                  <?php else: ?>
                    <p class="order-meta">Account: Guest — no site account (reply to the email above).</p>
                  <?php endif; ?>
                  <?php if ($responded && !empty($m["replied_at"])): ?>
                    <p class="order-meta">
                      Marked responded
                      <?php echo !empty($m["replier_name"]) ? " by " . htmlspecialchars($m["replier_name"], ENT_QUOTES, "UTF-8") : ""; ?>
                      · <?php echo htmlspecialchars(date("M j, Y g:i a", strtotime($m["replied_at"])), ENT_QUOTES, "UTF-8"); ?>
                      (reply sent manually by email)
                    </p>
                  <?php endif; ?>
                </div>

                 <?php if (!$responded): ?>
                   <div class="order-note">
                     <label for="reply-<?php echo (int) $m["id"]; ?>" class="order-note-label">
                       Your reply to this user:
                     </label>
                     <form method="post" action="messages.php">
                       <?php echo csrf_field(); ?>
                       <input type="hidden" name="action" value="reply" />
                       <input type="hidden" name="message_id" value="<?php echo (int) $m["id"]; ?>" />
                       <textarea
                         id="reply-<?php echo (int) $m["id"]; ?>"
                         name="reply_text"
                         class="reply-textarea"
                         rows="4"
                         placeholder="Type your reply here... (the user will see this in their My Messages page)"
                       ></textarea>
                       <button type="submit" class="btn btn-accent message-btn">Send Reply</button>
                     </form>
                     <p class="order-note-small">For guests without an account, copy this reply and email it to them.</p>
                   </div>
                 <?php else: ?>
                   <div class="user-reply">
                     <p><strong>Admin reply:</strong></p>
                     <p><?php echo nl2br(htmlspecialchars($m["reply"] ?? "", ENT_QUOTES, "UTF-8")); ?></p>
                   </div>
                 <?php endif; ?>

                <div class="message-actions">
                  <?php if ($responded): ?>
                    <form method="post" action="messages.php">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="mark_waiting" />
                      <input type="hidden" name="message_id" value="<?php echo (int) $m["id"]; ?>" />
                      <button type="submit" class="btn btn-outline message-btn">Back to Waiting</button>
                    </form>
                  <?php else: ?>
                    <form method="post" action="messages.php">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="mark_responded" />
                      <input type="hidden" name="message_id" value="<?php echo (int) $m["id"]; ?>" />
                      <button type="submit" class="btn btn-accent message-btn">Mark as Responded</button>
                    </form>
                  <?php endif; ?>
                  <?php if (!$m["is_read"]): ?>
                    <form method="post" action="messages.php">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="mark_read" />
                      <input type="hidden" name="message_id" value="<?php echo (int) $m["id"]; ?>" />
                      <button type="submit" class="btn btn-outline message-btn">Mark as read</button>
                    </form>
                  <?php endif; ?>
                  <form method="post" action="messages.php" onsubmit="return confirm('Delete this message?');">
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

<?php include __DIR__ . "/../includes/footer.php"; ?>