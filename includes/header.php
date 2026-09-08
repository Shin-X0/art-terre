<?php
/* ============================================================
   ART TERRE — shared header (head + nav + opening <main>)
   Pages set BEFORE including:
     $pageTitle  (optional) — <title> text
     $page       (optional) — "home" | "artists" | "artworks"
   ============================================================ */

$page     = $page     ?? "home";
$pageTitle = $pageTitle ?? "Art Terre Creations — Where Art Meets Opportunity";
$isHome   = ($page === "home");

/* On the landing page the nav uses in-page anchors;
   everywhere else it links back to the landing sections. */
$base = $isHome ? "" : "index.php";
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
  <link rel="stylesheet" href="css/style.css?v=16" />
</head>
<body>

  <!-- ======================= HEADER ======================= -->
  <header class="header" id="header">
    <nav class="navbar container">
      <!-- Logo (official brand image) -->
      <a href="<?php echo $isHome ? "#home" : "index.php"; ?>" class="logo" aria-label="Art Terre Creations — Home">
        <img class="logo-img" src="images/logo.png" alt="Art Terre Creations logo" />
      </a>

      <ul class="nav-menu" id="nav-menu">
        <li class="nav-item"><a href="<?php echo $isHome ? "#home" : "index.php"; ?>" class="nav-link<?php echo $isHome ? " active" : ""; ?>">Home</a></li>
        <li class="nav-item"><a href="<?php echo $base; ?>#artists" class="nav-link<?php echo $page === "artists" ? " active" : ""; ?>">Artist</a></li>
        <li class="nav-item"><a href="<?php echo $base; ?>#artworks" class="nav-link<?php echo $page === "artworks" ? " active" : ""; ?>">Artworks</a></li>
        <li class="nav-item"><a href="<?php echo $base; ?>#about" class="nav-link">About</a></li>
        <li class="nav-item"><a href="<?php echo $base; ?>#contact" class="nav-link">Contact</a></li>
      </ul>

      <!-- Header sign-in (email + button), as in the mockup -->
      <div class="header-signin">
        <form class="signin-form" id="signin-form" novalidate>
          <input type="email" name="email" id="signin-email" placeholder="Enter your Email Address" autocomplete="email" required />
          <button type="submit" class="btn btn-accent">Sign in</button>
        </form>
        <p class="form-msg" id="signin-msg" role="status" aria-live="polite"></p>
      </div>

      <!-- Cart -->
      <button class="cart-btn" id="cart-btn" aria-label="Shopping cart">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
          <path d="M2.5 3.5h2.6l2.5 11.2a1.8 1.8 0 0 0 1.8 1.4h7.9a1.8 1.8 0 0 0 1.8-1.4L21 7.5H6.1"/>
        </svg>
        <span class="cart-count" id="cart-count">0</span>
      </button>

      <button class="hamburger" id="hamburger" aria-label="Open navigation menu" aria-expanded="false" aria-controls="nav-menu">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </button>
    </nav>
  </header>

  <main>