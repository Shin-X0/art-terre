<?php
/* ============================================================
   ART TERRE — Contact form handler (footer form on every page)
   ANY visitor can write (guest or signed in). The message is stored
   in contact_messages and lands in the ADMIN's inbox
   (admin/messages.php), where the admin receives it and replies.
   Signed-in senders read the admin's reply in pages/messages.php
   ("My messages"); guests get the answer by email.
   ============================================================ */
require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";

/* Only POST makes sense here — anything else goes home */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../index.php");
  exit;
}

/* Same-page redirect target (path-only, stays on this site) */
$redirect = $_POST["redirect"] ?? "../index.php";
if (!is_string($redirect) || strpos($redirect, "/") !== 0 || strpos($redirect, "//") === 0) {
  $redirect = "../index.php";
}
$back = $redirect . (strpos($redirect, "?") === false ? "?" : "&") . "contact=";

if (!csrf_check()) {
  header("Location: " . $back . "csrf");
  exit;
}

$name    = trim($_POST["name"] ?? "");
$email   = strtolower(trim($_POST["email"] ?? ""));
$message = trim(($_POST["message"] ?? "") . "\n" . ($_POST["message2"] ?? ""));

if (mb_strlen($name) < 2
    || !filter_var($email, FILTER_VALIDATE_EMAIL)
    || mb_strlen($message) < 5
    || mb_strlen($message) > 3000) {
  header("Location: " . $back . "invalid");
  exit;
}

$user    = current_user();
$userId  = $user["id"] ?? null;

$stmt = $db->prepare(
  "INSERT INTO contact_messages (user_id, name, email, message) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("isss", $userId, $name, $email, $message);
$stmt->execute();
$stmt->close();

header("Location: " . $back . "sent");
exit;