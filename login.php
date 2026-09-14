<?php
/* ============================================================
   ART TERRE — Login / Sign-in page
   Real sessions + MySQL (users table, password_hash).
   Demo account: demo@artterre.com / password123
   ============================================================ */
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/auth.php";

/* Already signed in? Nothing to do here. */
if (current_user()) {
  header("Location: index.php");
  exit;
}

$errors = [];                         /* field => message */
$old = [                              /* re-fill after failed submit */
  "name" => "", "email" => "",
  "dob" => "", "gender" => "", "role" => "collector",
];
$activeTab = "signin";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!csrf_check()) {
    $errors["form"] = "Your session expired — please try again.";
  } else {
    $action = $_POST["action"] ?? "";

    /* ---------- Create Account ---------- */
    if ($action === "register") {
      $activeTab = "register";
      $name   = trim($_POST["name"] ?? "");
      $email  = strtolower(trim($_POST["email"] ?? ""));
      $pass   = $_POST["password"] ?? "";
      $pass2  = $_POST["password2"] ?? "";
      $dob    = trim($_POST["dob"] ?? "");
      $gender = $_POST["gender"] ?? "";
      $role   = $_POST["role"] ?? "collector";
      $old["name"]   = $name;
      $old["email"]  = $email;
      $old["dob"]    = $dob;
      $old["gender"] = $gender;
      $old["role"]   = $role === "artist" ? "artist" : "collector";

      if (mb_strlen($name) < 2) {
        $errors["name"] = "Please enter your name (2+ characters).";
      }
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Enter a valid email address.";
      }
      if (strlen($pass) < 8) {
        $errors["password"] = "Password must be at least 8 characters.";
      }
      if ($pass !== $pass2) {
        $errors["password2"] = "Passwords do not match.";
      }

      /* Date of birth — required, real date, in the past, 13+ years old */
      $dobTs = $dob !== "" ? strtotime($dob) : false;
      if ($dobTs === false) {
        $errors["dob"] = "Pick your date of birth from the calendar.";
      } elseif ($dobTs > time()) {
        $errors["dob"] = "Date of birth cannot be in the future.";
      } elseif ($dobTs > strtotime("-13 years")) {
        $errors["dob"] = "You must be at least 13 years old to join.";
      }

      /* Gender — must be one of the listed options */
      $genders = ["female", "male", "non-binary", "other", "prefer-not"];
      if (!in_array($gender, $genders, true)) {
        $errors["gender"] = "Please choose a gender option.";
      }

      /* Role — collector by default, artists unlock the upload page */
      if (!in_array($role, ["collector", "artist"], true)) {
        $role = "collector";
      }

      if (!$errors) {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($exists) {
          $errors["email"] = "That email is already registered — sign in instead.";
        } else {
          $hash = password_hash($pass, PASSWORD_DEFAULT);
          $stmt = $db->prepare(
            "INSERT INTO users (name, email, password_hash, dob, gender, role)
             VALUES (?, ?, ?, ?, ?, ?)"
          );
          $stmt->bind_param("ssssss", $name, $email, $hash, $dob, $gender, $role);
          $stmt->execute();
          $userId = (int) $stmt->insert_id;
          $stmt->close();

          login_user($userId);
          header("Location: index.php");
          exit;
        }
      }
    }

    /* ---------- Sign In ---------- */
    elseif ($action === "signin") {
      $activeTab = "signin";
      $email = strtolower(trim($_POST["email"] ?? ""));
      $pass  = $_POST["password"] ?? "";
      $old["email"] = $email;

      /* Locked out after too many failed attempts — no more tries. */
      if (login_is_locked()) {
        $mins = (int) max(1, ceil(login_seconds_remaining() / 60));
        $errors["form"] = "Too many failed attempts — sign in is locked. Try again in about {$mins} minute(s).";
      }
      elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Enter a valid email address.";
      } else {
        $stmt = $db->prepare("SELECT id, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($pass, $user["password_hash"])) {
          login_clear_attempts();
          login_user((int) $user["id"]);
          header("Location: index.php");
          exit;
        }

        /* Failed attempt — 5 tries max, then a lockout. */
        if (login_record_failure()) {
          $errors["form"] = "Too many failed attempts — sign in is locked for "
            . (int) (LOGIN_LOCKOUT_SECONDS / 60) . " minute(s).";
        } else {
          $errors["form"] = "Wrong email or password. " . login_attempts_left()
            . " attempt(s) remaining.";
        }
      }
    }
  }
}

$pageTitle = "Login — Art Terre Creations";
$page = "signin";
include "includes/header.php";
?>

    <!-- ======================= PAGE HERO ======================= -->
    <section class="page-hero">
      <div class="container">
        <h1 class="page-hero-title reveal">Welcome Back</h1>
        <p class="page-hero-sub reveal">
          Sign in to keep your likes, cart, and orders in one place —
          or create an account in seconds.
        </p>
      </div>
    </section>

    <!-- ======================= AUTH CARD ======================= -->
    <section class="auth-section section" id="signin">
      <div class="container">
        <div class="auth-card reveal">

          <div class="auth-tabs" role="tablist" aria-label="Sign in or create an account">
            <button type="button" class="auth-tab active" data-tab="signin" role="tab" aria-selected="true">Login</button>
            <button type="button" class="auth-tab" data-tab="register" role="tab" aria-selected="false">Create Account</button>
          </div>

          <?php if (isset($errors["form"])): ?>
            <p class="auth-error" role="alert"><?php echo htmlspecialchars($errors["form"], ENT_QUOTES, "UTF-8"); ?></p>
          <?php endif; ?>

          <!-- ---------- Sign In ---------- -->
          <form class="auth-form" id="form-signin" method="post" action="login.php" novalidate <?php echo $activeTab !== "signin" ? "hidden" : ""; ?>>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="signin" />

            <div class="field">
              <label for="si-email">Email Address</label>
              <input type="email" id="si-email" name="email" value="<?php echo htmlspecialchars($old["email"], ENT_QUOTES, "UTF-8"); ?>" autocomplete="email" required />
              <?php if (isset($errors["email"])): ?>
                <p class="field-error"><?php echo htmlspecialchars($errors["email"], ENT_QUOTES, "UTF-8"); ?></p>
              <?php endif; ?>
            </div>

            <div class="field">
              <label for="si-password">Password</label>
              <input type="password" id="si-password" name="password" autocomplete="current-password" required />
            </div>

            <button type="submit" class="btn btn-accent auth-submit" id="si-submit"<?php if (login_is_locked()): ?> disabled data-lock-until="<?php echo time() + login_seconds_remaining(); ?>"<?php endif; ?>>Login</button>

            <p class="auth-switch">New to Art Terre? <a href="#" data-switch="register">Create an account</a></p>
            <p class="auth-hint">Demo account: demo@artterre.com / password123</p>
          </form>

          <!-- ---------- Create Account ---------- -->
          <form class="auth-form" id="form-register" method="post" action="login.php" novalidate <?php echo $activeTab !== "register" ? "hidden" : ""; ?>>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="register" />

            <div class="field">
              <label for="reg-name">Full Name</label>
              <input type="text" id="reg-name" name="name" value="<?php echo htmlspecialchars($old["name"], ENT_QUOTES, "UTF-8"); ?>" autocomplete="name" required />
              <?php if (isset($errors["name"])): ?>
                <p class="field-error"><?php echo htmlspecialchars($errors["name"], ENT_QUOTES, "UTF-8"); ?></p>
              <?php endif; ?>
            </div>

            <div class="field">
              <label for="reg-email">Email Address</label>
              <input type="email" id="reg-email" name="email" value="<?php echo htmlspecialchars($old["email"], ENT_QUOTES, "UTF-8"); ?>" autocomplete="email" required />
              <?php if (isset($errors["email"])): ?>
                <p class="field-error"><?php echo htmlspecialchars($errors["email"], ENT_QUOTES, "UTF-8"); ?></p>
              <?php endif; ?>
            </div>

            <div class="field">
              <label for="reg-password">Password (min. 8 characters)</label>
              <input type="password" id="reg-password" name="password" autocomplete="new-password" minlength="8" required />
              <?php if (isset($errors["password"])): ?>
                <p class="field-error"><?php echo htmlspecialchars($errors["password"], ENT_QUOTES, "UTF-8"); ?></p>
              <?php endif; ?>
            </div>

            <div class="field">
              <label for="reg-password2">Confirm Password</label>
              <input type="password" id="reg-password2" name="password2" autocomplete="new-password" required />
              <?php if (isset($errors["password2"])): ?>
                <p class="field-error"><?php echo htmlspecialchars($errors["password2"], ENT_QUOTES, "UTF-8"); ?></p>
              <?php endif; ?>
            </div>

            <div class="field">
              <label for="reg-dob">Date of Birth</label>
              <input type="date" id="reg-dob" name="dob" value="<?php echo htmlspecialchars($old["dob"], ENT_QUOTES, "UTF-8"); ?>" max="<?php echo date("Y-m-d", strtotime("-13 years")); ?>" required />
              <?php if (isset($errors["dob"])): ?>
                <p class="field-error"><?php echo htmlspecialchars($errors["dob"], ENT_QUOTES, "UTF-8"); ?></p>
              <?php endif; ?>
            </div>

            <div class="field">
              <label for="reg-gender">Gender</label>
              <select id="reg-gender" name="gender" required>
                <option value="" disabled <?php echo $old["gender"] === "" ? "selected" : ""; ?>>Select your gender…</option>
                <option value="female" <?php echo $old["gender"] === "female" ? "selected" : ""; ?>>Female</option>
                <option value="male" <?php echo $old["gender"] === "male" ? "selected" : ""; ?>>Male</option>
                <option value="non-binary" <?php echo $old["gender"] === "non-binary" ? "selected" : ""; ?>>Non-binary</option>
                <option value="other" <?php echo $old["gender"] === "other" ? "selected" : ""; ?>>Other</option>
                <option value="prefer-not" <?php echo $old["gender"] === "prefer-not" ? "selected" : ""; ?>>Prefer not to say</option>
              </select>
              <?php if (isset($errors["gender"])): ?>
                <p class="field-error"><?php echo htmlspecialchars($errors["gender"], ENT_QUOTES, "UTF-8"); ?></p>
              <?php endif; ?>
            </div>

            <fieldset class="field role-field">
              <legend>I am joining as…</legend>
              <div class="role-choices">
                <label class="role-choice">
                  <input type="radio" name="role" value="collector" <?php echo $old["role"] !== "artist" ? "checked" : ""; ?> />
                  <span class="role-card">
                    <strong>🎨 Collector</strong>
                    <small>Browse, like, and buy original artworks.</small>
                  </span>
                </label>
                <label class="role-choice">
                  <input type="radio" name="role" value="artist" <?php echo $old["role"] === "artist" ? "checked" : ""; ?> />
                  <span class="role-card">
                    <strong>🖌️ Artist</strong>
                    <small>Upload and sell your own artworks.</small>
                  </span>
                </label>
              </div>
            </fieldset>

            <p class="auth-error" hidden id="reg-error"></p>

            <button type="submit" class="btn btn-accent auth-submit">Create Account</button>

            <p class="auth-switch">Already have an account? <a href="#" data-switch="signin">Login</a></p>
          </form>

        </div>
      </div>
    </section>

<?php include "includes/footer.php"; ?>