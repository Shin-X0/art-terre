<?php
/* ============================================================
   ART TERRE — My Messages (signed-in users)
   Messages YOU sent via Contact Us. The admin can reply directly
   on the site — the reply appears here in your "My Messages" page.
   For guests the admin answers by email.
   Status: Waiting for reply  /  Responded (see the admin's reply below)
   ============================================================ */
require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";

$user = current_user();
if (!$user) {
  header("Location: login.php");
  exit;
}

$stmt = $db->prepare(
  "SELECT m.*
   FROM contact_messages m
   WHERE m.user_id = ?
   ORDER BY (m.reply IS NULL) ASC, m.created_at DESC, m.id DESC"
);
$stmt->bind_param("i", $user["id"]);
$stmt->execute();
$messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$pageTitle = "My Messages — Art Terre Creations";
$page = "messages";
include __DIR__ . "/../includes/header.php";
?>
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">My Messages</h1>
        <p class="page-hero-sub reveal">What you sent via Contact Us — the admin replies by email; track the status here.</p>
      </div>
    </section>

    <section class="section" id="my-messages">
      <div class="container">
        <?php if (!$messages): ?>
          <div class="checkout-success reveal">
            <h2>No messages yet</h2>
            <p>Use the Contact Us form at the bottom of any page — the admin will reply to your email.</p>
            <a href="../index.php#contact" class="btn btn-accent">Contact Us</a>
          </div>
        <?php else: ?>
          <div class="orders-list">
            <?php foreach ($messages as $m): ?>
              <?php $responded = array_key_exists("reply", $m) && $m["reply"] !== null; ?>
              <article class="order-card reveal">
                <div class="order-card-head">
                  <div>
                    <strong class="order-no">To Admin</strong>
                    <span class="order-date"><?php echo htmlspecialchars(date("M j, Y g:i a", strtotime($m["created_at"])), ENT_QUOTES, "UTF-8"); ?></span>
                  </div>
                  <?php if ($responded): ?>
                    <span class="status-badge st-delivered">Responded</span>
                  <?php else: ?>
                    <span class="status-badge st-pending">Waiting for reply</span>
                  <?php endif; ?>
                </div>
                <p class="message-text"><?php echo nl2br(htmlspecialchars($m["message"], ENT_QUOTES, "UTF-8")); ?></p>
                 <?php if ($responded): ?>
                   <div class="user-reply">
                     <p><strong>Admin reply:</strong></p>
                     <p><?php echo nl2br(htmlspecialchars($m["reply"], ENT_QUOTES, "UTF-8")); ?></p>
                   </div>
                 <?php else: ?>
                   <p class="order-note">Waiting for reply — the admin will reply to you soon.</p>
                 <?php endif; ?>
               </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>

<?php include __DIR__ . "/../includes/footer.php"; ?>