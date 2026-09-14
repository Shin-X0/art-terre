<?php
/* ============================================================
   ART TERRE — auth helpers (session user + CSRF)
   Requires includes/config.php to be loaded first.
   ============================================================ */

/* ---------- Current user (null when signed out) ---------- */
function current_user(): ?array {
  global $db;

  if (empty($_SESSION["user_id"])) return null;

  $stmt = $db->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
  $stmt->bind_param("i", $_SESSION["user_id"]);
  $stmt->execute();
  $user = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  /* Session pointed at a deleted user — treat as signed out */
  if (!$user) {
    unset($_SESSION["user_id"]);
    return null;
  }

  return $user;
}

/* ---------- Sign in / out ---------- */
function login_user(int $userId): void {
  session_regenerate_id(true);   /* prevent session fixation */
  $_SESSION["user_id"] = $userId;
}

function logout_user(): void {
  $_SESSION = [];

  if (ini_get("session.use_cookies")) {
    $p = session_get_cookie_params();
    setcookie(
      session_name(),
      "",
      time() - 42000,
      $p["path"],
      $p["domain"],
      $p["secure"],
      $p["httponly"]
    );
  }

  session_destroy();
}

/* ---------- Login attempt limiting (5 tries, then lockout) ---------- */
const MAX_LOGIN_ATTEMPTS    = 5;   /* failed sign-in tries allowed          */
const LOGIN_LOCKOUT_SECONDS = 300; /* lock duration after limit is reached  */

function login_is_locked(): bool {
  return ($_SESSION["login_lock_until"] ?? 0) > time();
}

function login_seconds_remaining(): int {
  return max(0, (int) ($_SESSION["login_lock_until"] ?? 0) - time());
}

/* Records a failed attempt; returns true when this failure triggers the lock. */
function login_record_failure(): bool {
  $_SESSION["login_attempts"] = ($_SESSION["login_attempts"] ?? 0) + 1;

  if ($_SESSION["login_attempts"] >= MAX_LOGIN_ATTEMPTS) {
    $_SESSION["login_lock_until"] = time() + LOGIN_LOCKOUT_SECONDS;
    return true;
  }

  return false;
}

function login_attempts_left(): int {
  return max(0, MAX_LOGIN_ATTEMPTS - ($_SESSION["login_attempts"] ?? 0));
}

function login_clear_attempts(): void {
  unset($_SESSION["login_attempts"], $_SESSION["login_lock_until"]);
}

/* ---------- CSRF protection ---------- */
function csrf_token(): string {
  if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
  }
  return $_SESSION["csrf_token"];
}

function csrf_field(): string {
  return '<input type="hidden" name="csrf_token" value="'
    . htmlspecialchars(csrf_token(), ENT_QUOTES, "UTF-8")
    . '">';
}

function csrf_check(): bool {
  return isset($_POST["csrf_token"], $_SESSION["csrf_token"])
    && hash_equals($_SESSION["csrf_token"], (string) $_POST["csrf_token"]);
}