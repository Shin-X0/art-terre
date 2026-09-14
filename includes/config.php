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

/* ---------- URL base prefix ----------
   Pages live one folder down (pages/ and admin/), so links and assets
   (css, images, js) are prefixed with "../" there. Computed from the
   filesystem location of the page relative to the site root, so it stays
   correct whether the site is served at the domain root or in a subfolder
   (e.g. http://localhost/art-terre/). */
$basePath = "";
$siteRoot = str_replace("\\", "/", dirname(__DIR__));
$pageDir  = str_replace("\\", "/", dirname($_SERVER["SCRIPT_FILENAME"] ?? __FILE__));
$siteRoot = rtrim(str_replace("\\", "/", realpath($siteRoot) ?: $siteRoot), "/");
$pageDir  = rtrim(str_replace("\\", "/", realpath($pageDir) ?: $pageDir), "/");
if ($pageDir !== $siteRoot && strpos($pageDir . "/", $siteRoot . "/") === 0) {
  $basePath = str_repeat("../", substr_count(substr($pageDir, strlen($siteRoot)), "/"));
}

/* ---------- Admins (role = admin, or emails listed here) ---------- */
const ADMIN_EMAILS = ["demo@artterre.com"];

function is_admin(?array $user): bool {
  if ($user === null) {
    return false;
  }
  if (($user["role"] ?? "") === "admin") {
    return true;
  }
  return in_array(strtolower($user["email"]), ADMIN_EMAILS, true);
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