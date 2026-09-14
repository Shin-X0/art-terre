<?php
/* ============================================================
   ART TERRE — shared config (session + database connection)
   Default XAMPP MySQL credentials — adjust if yours differ.
   ============================================================ */

declare(strict_types=1);

/* ---------- Session (HttpOnly cookie, 7 days) ---------- */
if (session_status() === PHP_SESSION_NONE) {
  session_set_cookie_params([
    "lifetime" => 60 * 60 * 24 * 7,
    "path"     => "/",
    "httponly" => true,
    "samesite" => "Lax",
  ]);
  session_start();
}

/* ---------- Database (XAMPP defaults) ---------- */
define("DB_HOST", "127.0.0.1");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "art_terre");

/* ---------- Admins (can manage order statuses) ---------- */
const ADMIN_EMAILS = ["demo@artterre.com"];

function is_admin(?array $user): bool {
  return $user !== null
    && in_array(strtolower($user["email"]), ADMIN_EMAILS, true);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
  $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  $db->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
  http_response_code(500);
  die(
    "Could not connect to the database. Start MySQL in the XAMPP control "
    . "panel and import db/art_terre.sql (see the file header for how)."
  );
}