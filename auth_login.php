<?php
ob_start();
session_start();

// Include required files (ensure these files don't output any content)
require_once('includes/DB.php');

$errors = [];

// Process the form only when a POST request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture and trim user input
    $mobileOrUsername = trim($_POST['mobile'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate required fields
    if (empty($mobileOrUsername)) {
        $errors[] = "User Name / E-Mail field is required.";
    }
    if (empty($password)) {
        $errors[] = "Password field is required.";
    }

    // Validate CSRF token using a timing-attack-safe comparison
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        $errors[] = "Invalid CSRF token.";
    }

    // Validate CSRF token expiration (24 hours validity)
    $max_time = 60 * 60 * 24; // 24 hours in seconds
    if (isset($_SESSION['csrf_token_time']) && (($_SESSION['csrf_token_time'] + $max_time) < time())) {
        $errors[] = "CSRF token expired.";
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
    }

    // If no errors, proceed with authentication
    if (empty($errors)) {
        // Determine whether the input is a mobile number (all digits) or a username
        if (filter_var($mobileOrUsername, FILTER_VALIDATE_EMAIL)) {
    $loginField = 'mobile';
} elseif (ctype_digit($mobileOrUsername)) {
    $loginField = 'mobile';
} else {
    $loginField = 'username';
}


        // Prepare and execute the SQL statement to fetch user data
        $sql = "SELECT id, username, password FROM users WHERE {$loginField} = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$mobileOrUsername]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password if a user was found
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            $_SESSION['login'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['last_login'] = time();

            // Redirect to the dashboard
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "User Name / E-Mail & Password combination not working.";
        }
    }
}

// Generate a new CSRF token securely
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>stellarPay Admin - stellarPay Management System</title>
    <meta name="description" content="Manage your fintech services with the stellarPay Admin panel. Effortlessly manage transactions, agents, and wallet operations with our powerful system." />
    <meta content="fintech, mobile money, digital wallet, transaction management, admin panel, stellarPay" name="keywords">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://southernstar.tech/stellarpay">
    <meta property="og:title" content="stellarPay - Mobile Money Meets Blockchain Technology">
    <meta property="og:description" content="Experience secure, instant money transfers at lowest fees in Tanzania with Stellar blockchain-powered mobile wallet.">
    <meta property="og:image" content="https://southernstar.tech/stellarpay/assets/img/cover.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://southernstar.tech/stellarpay">
    <meta property="twitter:title" content="stellarPay | Next-Gen Mobile Money Platform">
    <meta property="twitter:description" content="Bank the unbanked with Stellar blockchain technology. Mobile payments made secure, fast, and affordable across East Africa.">
    <meta property="twitter:image" content="https://southernstar.tech/stellarpay/assets/img/cover.png">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://southernstar.tech/stellarpay">

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "stellarPay",
        "url": "https://southernstar.tech/stellarpay",
        "logo": "https://southernstar.tech/assets/img/cover.png",
        "description": "Mobile money platform leveraging Stellar blockchain for affordable financial services",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Dar es Salaam",
            "addressRegion": "TZ",
            "postalCode": "30014",
            "addressCountry": "Tanzania"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+255-759-876-418",
            "contactType": "customer service"
        }
    }
    </script>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/vendor/fonts/materialdesignicons.css" />
    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>

    <meta name="theme-color" content="#0d6efd">

    <!-- iOS Support -->
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link rel="manifest" href="/stellarPay/manifest.json" crossorigin="use-credentials">
</head>


<body>
<!-- Content -->
<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <!-- Login Card -->
      <div class="card p-2">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="index.html" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
              <img src="assets/img/logoWeb.png" alt="stellarPay Logo" width="20" height="20" />
            </span>
            <span class="app-brand-text demo text-heading fw-semibold">stellarPay</span>
          </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-2">
          <h4 class="mb-2 text-center">Sign in to stellarPay</h4>
          <p class="mb-4 text-center">Access your dashboard and manage your wallet</p>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <?php foreach ($errors as $error): ?>
                <i class="fas fa-times"></i>&nbsp;<?= htmlspecialchars($error) ?><br>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <form id="formAuthentication" class="mb-3" action="" method="POST">
            <!-- Hidden CSRF token field -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">
            <div class="form-floating form-floating-outline mb-3">
              <input type="text" class="form-control" id="email" name="mobile" placeholder="Enter your phone or email" autofocus />
              <label for="email">Phone or Email</label>
            </div>
            <div class="mb-3">
              <div class="form-password-toggle">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" aria-describedby="password" />
                    <label for="password">Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                </div>
              </div>
            </div>
            <div class="mb-3 d-flex justify-content-between">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember-me" />
                <label class="form-check-label" for="remember-me"> Keep me signed in </label>
              </div>
              <a href="auth-forgot-password.php" class="float-end mb-1">
                <span>Forgot password?</span>
              </a>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Log In</button>
            </div>
          </form>

          <p class="text-center">
            <span>New to stellarPay?</span>
            <a href="auth-register.php">
              <span>Create your account</span>
            </a>
          </p>

          <!-- Install Button Section -->
          <div class="text-center mt-3">
            <button id="installApp" 
                    class="btn btn-outline-primary" 
                    style="display: none;"
                    aria-label="Install stellarPay App"
                    data-install-state="available">
              <i class="mdi mdi-download me-2"></i>
              <span class="install-text">Get stellarPay App</span>
              <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
            </button>
            <div id="installFeedback" class="small mt-2" aria-live="polite"></div>
          </div>

        </div>
      </div>
      <!-- /Login Card -->
    </div>
  </div>
</div>
<!-- /Content -->


    <!-- Core JS -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    <!-- Page JS -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
	
</body>
</html>
