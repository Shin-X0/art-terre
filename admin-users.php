<?php
/* ============================================================
   ART TERRE — Manage Users (admin only)
   Change roles (collector / artist / admin) and delete users.
   You cannot change or delete your own account here.
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

$ROLES = ["collector", "artist", "admin"];

/* ---------- POST: set role / delete user ---------- */
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
    }

    if ($action === "delete_user") {
      /* Remove the user's uploaded artwork images before the row goes */
      $stmt = $db->prepare("SELECT image_path FROM artworks WHERE artist_id = ? AND image_path IS NOT NULL");
      $stmt->bind_param("i", $userId);
      $stmt->execute();
      $res = $stmt->get_result();
      while ($row = $res->fetch_assoc()) {
        $file = __DIR__ . "/" . $row["image_path"];
        if (is_file($file)) unlink($file);
      }
      $stmt->close();

      $stmt = $db->prepare("DELETE FROM users WHERE id = ?"); /* artworks cascade */
      $stmt->bind_param("i", $userId);
      $stmt->execute();
      $stmt->close();
    }
  }

  header("Location: admin-users.php");
  exit;
}

$users = $db->query(
  "SELECT u.id, u.name, u.email, u.role, u.created_at,
          (SELECT COUNT(*) FROM artworks a WHERE a.artist_id = u.id) AS artwork_count
   FROM users u ORDER BY u.created_at DESC, u.id DESC"
)->fetch_all(MYSQLI_ASSOC);

$pageTitle = "Manage Users — Art Terre Creations";
$page = "checkout";
include "includes/header.php";
?>