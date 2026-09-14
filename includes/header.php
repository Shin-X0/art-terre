<?php
/* ============================================================
   ART TERRE — shared header (head + nav + opening <main>)
   Pages set BEFORE including:
     $pageTitle  (optional) — <title> text
     $page       (optional) — "home" | "artists" | "artworks" | ...
   ============================================================ */

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth.php";
$currentUser = current_user();

$page     = $page     ?? "home";
$pageTitle = $pageTitle ?? "Art Terre Creations — Where Art Meets Opportunity";
$isHome   = ($page === "home");

/* On the landing page the nav uses in-page anchors;
   everywhere else it links back to the landing sections.
   $basePath (from config.php) keeps the links right on pages/ and admin/. */
$base = $isHome ? "" : $basePath . "index.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $pageTitle; ?></title>
  <meta name="description" content="Art Terre is a sustainable platform bridging artists and collectors worldwide. Showcase authentic creativity and help every artist thrive." />

  <!-- Brand typefaces (Brand Guide 101):
       Headings — Space Grotesk Bold (Google Fonts, display=swap keeps loading fast)
       Body — Poppins (Google Fonts webfont substitute for Tw Cen MT, renders on all devices) -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo $basePath; ?>css/style.css?v=26" />
</head>
<body>

  <!-- ======================= HEADER ======================= -->
  <header class="header" id="header">
    <nav class="navbar container">
      <!-- Logo (official brand image) -->
      <a href="<?php echo $isHome ? "#home" : $basePath . "index.php"; ?>" class="logo" aria-label="Art Terre Creations — Home">
        <img class="logo-img" src="<?php echo $basePath; ?>images/logo.png" alt="Art Terre Creations logo" />
      </a>

      <ul class="nav-menu" id="nav-menu">
        <li class="nav-item"><a href="<?php echo $isHome ? "#home" : $basePath . "index.php"; ?>" class="nav-link<?php echo $isHome ? " active" : ""; ?>">Home</a></li>
        <li class="nav-item"><a href="<?php echo $base; ?>#artists" class="nav-link<?php echo $page === "artists" ? " active" : ""; ?>">Artist</a></li>
        <li class="nav-item"><a href="<?php echo $base; ?>#artworks" class="nav-link<?php echo $page === "artworks" ? " active" : ""; ?>">Artworks</a></li>
        <li class="nav-item"><a href="<?php echo $base; ?>#about" class="nav-link">About</a></li>
        <li class="nav-item"><a href="<?php echo $base; ?>#contact" class="nav-link">Contact</a></li>
      </ul>

      <!-- Account: sign-in button (guest) or avatar chip + dropdown menu -->
      <div class="header-account">
        <?php if ($currentUser): ?>
          <?php
            $firstName = htmlspecialchars(explode(" ", trim($currentUser["name"]))[0], ENT_QUOTES, "UTF-8");
            $initial = htmlspecialchars(mb_strtoupper(mb_substr($currentUser["name"], 0, 1)), ENT_QUOTES, "UTF-8");
            $emailSafe = htmlspecialchars($currentUser["email"], ENT_QUOTES, "UTF-8");
            $isArtist = (($currentUser["role"] ?? "collector") === "artist");
            $isAdmin = is_admin($currentUser);
            /* Sellers are sell-only: My Sales (update THEIR orders' status),
               never My Orders / checkout. Buyers get My Orders (view-only). */
            $isSellerOnly = $isArtist && !$isAdmin;
          ?>
          <div class="account-menu" id="account-menu">
            <button class="user-chip" id="account-toggle" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="account-dropdown" title="<?php echo $emailSafe; ?>">
              <span class="user-avatar"><?php echo $initial; ?></span>
              <span class="user-name">Hi, <?php echo $firstName; ?></span>
              <span class="chip-bars" aria-hidden="true"><span></span><span></span><span></span></span>
            </button>
            <div class="account-dropdown" id="account-dropdown" role="menu" aria-labelledby="account-toggle">
              <p class="account-email" title="<?php echo $emailSafe; ?>"><?php echo $emailSafe; ?></p>
              <?php if ($isArtist): ?>
                <a class="account-item" href="<?php echo $basePath; ?>pages/upload-artwork.php" role="menuitem">Sell artwork</a>
              <?php endif; ?>
              <?php if ($isSellerOnly): ?>
                <a class="account-item" href="<?php echo $basePath; ?>pages/sales.php" role="menuitem">My sales</a>
              <?php endif; ?>
              <?php if ($isAdmin): ?>
                <a class="account-item" href="<?php echo $basePath; ?>admin/orders.php" role="menuitem">Manage orders</a>
                <a class="account-item" href="<?php echo $basePath; ?>admin/messages.php" role="menuitem">Inbox</a>
              <?php endif; ?>
              <?php if (!$isSellerOnly): ?>
                <a class="account-item" href="<?php echo $basePath; ?>pages/orders.php" role="menuitem">My orders</a>
              <?php endif; ?>
              <a class="account-item" href="<?php echo $basePath; ?>pages/messages.php" role="menuitem">My messages</a>
              <a class="account-item account-signout" href="<?php echo $basePath; ?>pages/logout.php" role="menuitem">Sign out</a>
            </div>
          </div>
        <?php else: ?>
          <a class="btn btn-accent signin-btn" href="<?php echo $basePath; ?>pages/login.php">Sign in</a>
        <?php endif; ?>
      </div>

      <!-- Cart (links to the cart page) -->
      <a class="cart-btn" id="cart-btn" href="<?php echo $basePath; ?>pages/cart.php" aria-label="Shopping cart">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
          <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
        </svg>
        <span class="cart-count" id="cart-count">0</span>
      </a>

      <button class="hamburger" id="hamburger" aria-label="Open navigation menu" aria-expanded="false" aria-controls="nav-menu">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </button>
    </nav>
  </header>

  <main>
