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

/* ============================================================
   ROLE RULES (enforced server-side in checkout.php + orders):
   - collector (buyer): BUY only, order status is view-only.
   - artist (seller/owner): SELL only (cannot buy / checkout),
     but CAN update the status of orders containing THEIR artworks.
   - admin: full access (manage all orders + users).
   ============================================================ */
function is_artist(?array $user): bool {
  return $user !== null && ($user["role"] ?? "") === "artist";
}

function is_collector(?array $user): bool {
  return $user !== null && ($user["role"] ?? "") === "collector";
}

/* Order-item columns added by db/migrate_order_ownership.sql.
   True when order_items has artwork_id/artist_id (live DB already
   migrated above); code paths fall back gracefully when false. */
function order_items_have_ownership(): bool {
  global $db;
  static $has = null;
  if ($has !== null) return $has;
  try {
    $res = $db->query("SHOW COLUMNS FROM order_items LIKE 'artist_id'");
    $has = $res && $res->num_rows > 0;
    if ($res) $res->close();
  } catch (mysqli_sql_exception $e) {
    $has = false;
  }
  return $has;
}

/* Can this user change this order's status?
   - admin: always yes.
   - seller/artist: yes ONLY when at least one line of the order
     belongs to them (order_items.artist_id = their id).
   - buyer/collector: never (view-only). */
function can_update_order_status(?array $user, int $orderId): bool {
  global $db;
  if ($user === null || $orderId <= 0) return false;
  if (is_admin($user)) return true;
  if (!is_artist($user)) return false;
  if (!order_items_have_ownership()) return false;
  $stmt = $db->prepare(
    "SELECT 1 FROM order_items WHERE order_id = ? AND artist_id = ? LIMIT 1"
  );
  $stmt->bind_param("ii", $orderId, $user["id"]);
  $stmt->execute();
  $ok = (bool) $stmt->get_result()->fetch_row();
  $stmt->close();
  return $ok;
}

/* Order ids that contain at least one artwork owned by this seller. */
function seller_order_ids(int $artistId): array {
  global $db;
  if (!order_items_have_ownership()) return [];
  $stmt = $db->prepare(
    "SELECT DISTINCT order_id FROM order_items WHERE artist_id = ?"
  );
  $stmt->bind_param("i", $artistId);
  $stmt->execute();
  $rows = $stmt->get_result()->fetch_all(MYSQLI_NUM);
  $stmt->close();
  return array_map(fn($r) => (int) $r[0], $rows);
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