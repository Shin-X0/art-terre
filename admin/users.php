<?php
/* ============================================================
   ART TERRE — Manage Users (admin only)
   Change roles (collector / artist / admin) and delete users.
   You cannot change or delete your own account here.
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

$ROLES = ["collector", "artist", "admin"];
$ROLE_LABELS = [
  "collector" => "Collector",
  "artist"    => "Artist",
  "admin"     => "Admin",
];

/* ---------- POST: set role / delete user ---------- */
$flash = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && csrf_check()) {
  $action = $_POST["action"] ?? "";
  $userId = (int) ($_POST["user_id"] ?? 0);

  /* Never allow an admin to modify their own account here */
  if ($userId > 0 && $userId !== (int) $currentUser["id"]) {
    if ($action === "set_role" && in_array($_POST["role"] ?? "", $ROLES, true)) {
      $stmt = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
      $stmt->bind_param("si", $_POST["role"], $userId);
      $stmt->execute();
      $stmt->close();
      $flash = "updated";
    }

    if ($action === "delete_user") {
      /* Remove the user's uploaded artwork images before the row goes */
      $stmt = $db->prepare("SELECT image_path FROM artworks WHERE artist_id = ? AND image_path IS NOT NULL");
      $stmt->bind_param("i", $userId);
      $stmt->execute();
      $res = $stmt->get_result();
      while ($row = $res->fetch_assoc()) {
        $file = __DIR__ . "/../" . $row["image_path"];
        if (is_file($file)) unlink($file);
      }
      $stmt->close();

      $stmt = $db->prepare("DELETE FROM users WHERE id = ?"); /* artworks cascade */
      $stmt->bind_param("i", $userId);
      $stmt->execute();
      $stmt->close();
      $flash = "deleted";
    }
  }

  header("Location: users.php" . ($flash !== "" ? "?{$flash}=1" : ""));
  exit;
}

$users = $db->query(
  "SELECT u.id, u.name, u.email, u.role, u.created_at,
          (SELECT COUNT(*) FROM artworks a WHERE a.artist_id = u.id) AS artwork_count
   FROM users u ORDER BY u.created_at DESC, u.id DESC"
)->fetch_all(MYSQLI_ASSOC);

$pageTitle = "Manage Users — Art Terre Creations";
$page = "checkout";
include __DIR__ . "/../includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Manage Users</h1>
        <p class="page-hero-sub reveal">
          Change roles (collector / artist / admin) or remove accounts — you can't
          change or delete your own account here.
        </p>
      </div>
    </section>

    <!-- ======================= USERS ======================= -->
    <section class="checkout-section section" id="admin-users">
      <div class="container">

        <?php if (isset($_GET["updated"])): ?>
          <p class="upload-flash" role="status">User role updated.</p>
        <?php elseif (isset($_GET["deleted"])): ?>
          <p class="upload-flash" role="status">User removed.</p>
        <?php endif; ?>

        <?php if (!$users): ?>
          <div class="checkout-success reveal">
            <h2>No users yet</h2>
            <p>Accounts created on the site will show up here.</p>
          </div>

        <?php else: ?>
          <div class="orders-list">
            <?php foreach ($users as $u): ?>
              <article class="order-card reveal">
                <div class="order-card-head">
                  <div>
                    <strong class="order-no"><?php echo htmlspecialchars($u["name"], ENT_QUOTES, "UTF-8"); ?></strong>
                    <span class="order-date">
                      <?php echo htmlspecialchars($u["email"], ENT_QUOTES, "UTF-8"); ?>
                      · <?php echo (int) $u["artwork_count"]; ?> artwork<?php echo (int) $u["artwork_count"] === 1 ? "" : "s"; ?>
                      · joined <?php echo htmlspecialchars(date("M j, Y", strtotime($u["created_at"])), ENT_QUOTES, "UTF-8"); ?>
                    </span>
                  </div>
                  <span class="status-badge st-<?php echo htmlspecialchars($u["role"], ENT_QUOTES, "UTF-8"); ?>">
                    <?php echo htmlspecialchars($ROLE_LABELS[$u["role"]] ?? ucfirst($u["role"]), ENT_QUOTES, "UTF-8"); ?>
                  </span>
                </div>

                <?php if ((int) $u["id"] === (int) $currentUser["id"]): ?>
                  <p class="order-meta">This is you — use the sign-in page to manage your own account.</p>
                <?php else: ?>
                  <div class="user-actions">
                    <form class="order-status-form" method="post" action="users.php">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="set_role" />
                      <input type="hidden" name="user_id" value="<?php echo (int) $u["id"]; ?>" />

                      <label class="sr-only" for="role-<?php echo (int) $u["id"]; ?>">Role</label>
                      <select id="role-<?php echo (int) $u["id"]; ?>" name="role">
                        <?php foreach ($ROLES as $role): ?>
                          <option value="<?php echo $role; ?>" <?php echo $u["role"] === $role ? "selected" : ""; ?>><?php echo $ROLE_LABELS[$role]; ?></option>
                        <?php endforeach; ?>
                      </select>
                      <button type="submit" class="btn btn-accent order-status-save">Save Role</button>
                    </form>

                    <form method="post" action="users.php" onsubmit="return confirm('Delete this user? Their artworks are removed too.');">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="delete_user" />
                      <input type="hidden" name="user_id" value="<?php echo (int) $u["id"]; ?>" />
                      <button type="submit" class="remove-btn">Delete</button>
                    </form>
                  </div>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      </div>
    </section>

<?php include __DIR__ . "/../includes/footer.php"; ?>