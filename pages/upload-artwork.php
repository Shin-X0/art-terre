<?php
/* ============================================================
   ART TERRE — Sell an Artwork (artist-only upload page)
   Artists upload artworks to sell + manage their listings.
   Collectors see a notice instead. Requires the artworks table
   (db/migrate_users_artworks.sql).
   ============================================================ */
require_once __DIR__ . "/../includes/config.php";
require_once __DIR__ . "/../includes/auth.php";

$user = current_user();
if (!$user) {
  header("Location: login.php");
  exit;
}

/* Collectors can view the page but only get a notice — no form, no POST. */
$isArtist = ($user["role"] ?? "collector") === "artist";

$errors = [];
$old = ["title" => "", "category" => "painting", "price" => "", "story" => ""];

$UPLOAD_DIR   = __DIR__ . "/../images/uploads/artworks";
$MAX_BYTES    = 5 * 1024 * 1024;                 /* 5 MB per image        */
$ALLOWED_MIME = [                                /* extension by real mime */
  "image/jpeg" => "jpg",
  "image/png"  => "png",
  "image/webp" => "webp",
  "image/gif"  => "gif",
];
$CATEGORIES = ["painting", "photography"];

/* ---------- POST: remove / upload (artists only) ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && $isArtist) {
  if (!csrf_check()) {
    $errors["form"] = "Your session expired — please try again.";
  } else {
    $action = $_POST["action"] ?? "";

    /* ----- Remove one of my artworks ----- */
    if ($action === "remove") {
      $artId = (int) ($_POST["artwork_id"] ?? 0);

      $stmt = $db->prepare("SELECT image_path FROM artworks WHERE id = ? AND artist_id = ?");
      $stmt->bind_param("ii", $artId, $user["id"]);
      $stmt->execute();
      $row = $stmt->get_result()->fetch_assoc();
      $stmt->close();

      if ($row) {
        if (!empty($row["image_path"])) {
          $file = $UPLOAD_DIR . "/" . basename($row["image_path"]);
          if (is_file($file)) unlink($file);          /* keep the folder clean */
        }
        $stmt = $db->prepare("DELETE FROM artworks WHERE id = ? AND artist_id = ?");
        $stmt->bind_param("ii", $artId, $user["id"]);
        $stmt->execute();
        $stmt->close();
      }

      header("Location: upload-artwork.php?removed=1");
      exit;
    }

    /* ----- Upload a new artwork ----- */
    if ($action === "upload") {
      $title    = trim($_POST["title"] ?? "");
      $category = $_POST["category"] ?? "painting";
      $price    = trim($_POST["price"] ?? "");
      $story    = trim($_POST["story"] ?? "");
      $old["title"]    = $title;
      $old["category"] = $category;
      $old["price"]    = $price;
      $old["story"]    = $story;

      if (mb_strlen($title) < 2 || mb_strlen($title) > 150) {
        $errors["title"] = "Give your artwork a title (2–150 characters).";
      }
      if (!in_array($category, $CATEGORIES, true)) {
        $errors["category"] = "Choose a category.";
      }
      if (!is_numeric($price) || (float) $price <= 0 || (float) $price > 99999.99) {
        $errors["price"] = "Enter a price between $0.01 and $99,999.99.";
      }
      if (mb_strlen($story) > 2000) {
        $errors["story"] = "Keep the story under 2000 characters.";
      }

      /* ----- Image: required, size + real-type checked ----- */
      $file = $_FILES["image"] ?? null;
      $mime = "";
      if (!$file || $file["error"] !== UPLOAD_ERR_OK) {
        $errors["image"] = "Please choose an artwork image (JPG, PNG, WebP, or GIF).";
      } elseif ($file["size"] > $MAX_BYTES) {
        $errors["image"] = "Image is too large — 5 MB max.";
      } else {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = (string) $finfo->file($file["tmp_name"]);
        if (!isset($ALLOWED_MIME[$mime])) {
          $errors["image"] = "Only JPG, PNG, WebP, or GIF images are allowed.";
        }
      }

      if (!$errors) {
        if (!is_dir($UPLOAD_DIR)) {
          mkdir($UPLOAD_DIR, 0775, true);
        }

        $newName = date("Ymd_His") . "_" . bin2hex(random_bytes(6))
                 . "." . $ALLOWED_MIME[$mime];
        $dest = $UPLOAD_DIR . "/" . $newName;

        if (!move_uploaded_file($file["tmp_name"], $dest)) {
          $errors["form"] = "Could not save the image — please try again.";
        } else {
          $imagePath = "images/uploads/artworks/" . $newName;
          $priceVal  = round((float) $price, 2);
          $storyVal  = $story !== "" ? $story : null;

          $stmt = $db->prepare(
            "INSERT INTO artworks (artist_id, title, category, story, price, image_path)
             VALUES (?, ?, ?, ?, ?, ?)"
          );
          $stmt->bind_param("isssds", $user["id"], $title, $category, $storyVal, $priceVal, $imagePath);
          $stmt->execute();
          $stmt->close();

          /* Post/Redirect/Get — a refresh can't re-upload the artwork */
          header("Location: upload-artwork.php?added=1");
          exit;
        }
      }
    }
  }
}

/* ---------- My current listings ---------- */
$myArtworks = [];
$stmt = $db->prepare(
  "SELECT id, title, category, story, price, image_path, status, created_at
   FROM artworks WHERE artist_id = ? ORDER BY created_at DESC, id DESC"
);
$stmt->bind_param("i", $user["id"]);
$stmt->execute();
$myArtworks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$pageTitle = "Sell an Artwork — Art Terre Creations";
$page = "artworks";
include __DIR__ . "/../includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Sell an Artwork</h1>
        <p class="page-hero-sub reveal">
          Share your latest piece with collectors around the world —
          upload it once and it appears on the Artworks page instantly.
        </p>
      </div>
    </section>

    <!-- ======================= UPLOAD ======================= -->
    <section class="upload-section section" id="upload">
      <div class="container">

        <?php if (!$isArtist): ?>
          <!-- Collectors: friendly notice, no upload form -->
          <div class="upload-notice reveal">
            <h2>🎨 Uploading is for artists</h2>
            <p>
              You're signed in as a <strong>Collector</strong> — you can browse, like,
              and buy artworks, but only <strong>Artist</strong> accounts can upload
              pieces to sell. Create a new account and pick “Artist” to start selling.
            </p>
            <div class="upload-notice-actions">
              <a href="artworks.php" class="btn btn-outline">Browse Artworks</a>
              <a href="logout.php" class="btn btn-accent">Switch to an Artist account</a>
            </div>
          </div>

        <?php else: ?>

          <?php if (isset($_GET["added"])): ?>
            <p class="upload-flash" role="status">🎨 Your artwork is live on the <a href="artworks.php">Artworks page</a>.</p>
          <?php elseif (isset($_GET["removed"])): ?>
            <p class="upload-flash" role="status">Artwork removed from the shop.</p>
          <?php endif; ?>

          <?php if (isset($errors["form"])): ?>
            <p class="auth-error"><?php echo htmlspecialchars($errors["form"], ENT_QUOTES, "UTF-8"); ?></p>
          <?php endif; ?>

          <div class="upload-layout">
            <!-- ---------- Upload form ---------- -->
            <form class="upload-form reveal" method="post" action="upload-artwork.php" enctype="multipart/form-data" novalidate>
              <?php echo csrf_field(); ?>
              <input type="hidden" name="action" value="upload" />

              <div class="field">
                <label for="up-title">Artwork Title</label>
                <input type="text" id="up-title" name="title" value="<?php echo htmlspecialchars($old["title"], ENT_QUOTES, "UTF-8"); ?>" maxlength="150" required />
                <?php if (isset($errors["title"])): ?>
                  <p class="field-error"><?php echo htmlspecialchars($errors["title"], ENT_QUOTES, "UTF-8"); ?></p>
                <?php endif; ?>
              </div>

              <div class="field-row">
                <div class="field">
                  <label for="up-category">Category</label>
                  <select id="up-category" name="category" required>
                    <option value="painting" <?php echo $old["category"] === "painting" ? "selected" : ""; ?>>Painting</option>
                    <option value="photography" <?php echo $old["category"] === "photography" ? "selected" : ""; ?>>Photography</option>
                  </select>
                  <?php if (isset($errors["category"])): ?>
                    <p class="field-error"><?php echo htmlspecialchars($errors["category"], ENT_QUOTES, "UTF-8"); ?></p>
                  <?php endif; ?>
                </div>

                <div class="field">
                  <label for="up-price">Price (Php)</label>
                  <input type="number" id="up-price" name="price" min="0.01" max="99999.99" step="0.01" value="<?php echo htmlspecialchars($old["price"], ENT_QUOTES, "UTF-8"); ?>" required />
                  <?php if (isset($errors["price"])): ?>
                    <p class="field-error"><?php echo htmlspecialchars($errors["price"], ENT_QUOTES, "UTF-8"); ?></p>
                  <?php endif; ?>
                </div>
              </div>

              <div class="field">
                <label for="up-story">The Story <small>(optional)</small></label>
                <textarea id="up-story" name="story" rows="4" maxlength="2000" placeholder="What inspired this piece?"><?php echo htmlspecialchars($old["story"], ENT_QUOTES, "UTF-8"); ?></textarea>
                <?php if (isset($errors["story"])): ?>
                  <p class="field-error"><?php echo htmlspecialchars($errors["story"], ENT_QUOTES, "UTF-8"); ?></p>
                <?php endif; ?>
              </div>

              <div class="field">
                <label for="up-image">Artwork Image</label>
                <input type="file" id="up-image" name="image" accept="image/jpeg,image/png,image/webp,image/gif" required />
                <p class="field-hint">JPG, PNG, WebP, or GIF — 5 MB max.</p>
                <?php if (isset($errors["image"])): ?>
                  <p class="field-error"><?php echo htmlspecialchars($errors["image"], ENT_QUOTES, "UTF-8"); ?></p>
                <?php endif; ?>
              </div>

              <button type="submit" class="btn btn-accent upload-submit">Upload Artwork</button>
            </form>

            <!-- ---------- My listings ---------- -->
            <aside class="my-artworks reveal">
              <h2 class="my-artworks-title">My Artworks (<?php echo count($myArtworks); ?>)</h2>

              <?php if (!$myArtworks): ?>
                <p class="my-artworks-empty">
                  You haven't uploaded anything yet — your first piece will appear here
                  and go straight onto the Artworks page.
                </p>
              <?php else: ?>
                <div class="my-artworks-list">
                  <?php foreach ($myArtworks as $art): ?>
                    <div class="my-artwork-item">
                      <?php if (!empty($art["image_path"]) && is_file(__DIR__ . "/../" . $art["image_path"])): ?>
                        <img class="my-artwork-thumb" src="<?php echo htmlspecialchars($basePath . $art["image_path"], ENT_QUOTES, "UTF-8"); ?>" alt="" />
                      <?php else: ?>
                        <span class="my-artwork-thumb my-artwork-thumb--empty" aria-hidden="true"></span>
                      <?php endif; ?>

                      <div class="my-artwork-info">
                        <strong><?php echo htmlspecialchars($art["title"], ENT_QUOTES, "UTF-8"); ?></strong>
                        <span class="my-artwork-meta">
                          <?php echo htmlspecialchars(ucfirst($art["category"]), ENT_QUOTES, "UTF-8"); ?>
                          · $<?php echo number_format((float) $art["price"], 2); ?>
                          · <?php echo $art["status"] === "sold" ? "Sold" : "Available"; ?>
                        </span>
                      </div>

                      <form method="post" action="upload-artwork.php" onsubmit="return confirm('Remove this artwork from the shop?');">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="action" value="remove" />
                        <input type="hidden" name="artwork_id" value="<?php echo (int) $art["id"]; ?>" />
                        <button type="submit" class="remove-btn">Remove</button>
                      </form>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </aside>
          </div>

        <?php endif; ?>
      </div>
    </section>

<?php include __DIR__ . "/../includes/footer.php"; ?>

