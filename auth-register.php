<?php
ob_start();
session_start();
require_once('includes/DB.php');

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = md5(uniqid(rand(), true));
    $_SESSION['csrf_token_time'] = time();
}

// Your encryption key (⚠️ move to .env in production)
$encryption_key = '1234567890000';

function generateAgentCode($length = 8) {
    return str_pad(mt_rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

// Generate agent code (make sure it's unique)
$agentCode = generateAgentCode();

$errors = [];
$messages = [];


// Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid CSRF token.";
    }

    // CSRF Token Timeout (24 hours)
    $max_time = 86400;
    if (isset($_SESSION['csrf_token_time']) && (time() - $_SESSION['csrf_token_time']) > $max_time) {
        $errors[] = "CSRF token expired.";
    }

    // Validate fields
    if (empty($_POST['username'])) $errors[] = "Username is required.";
    if (empty($_POST['mobile'])) $errors[] = "Mobile is required.";
    if (empty($_POST['password'])) $errors[] = "Password is required.";
    if ($_POST['password'] !== $_POST['passwordr']) $errors[] = "Passwords do not match.";

    // Check uniqueness
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR mobile = ?");
    $stmt->execute([$_POST['username'], $_POST['mobile']]);
    if ($stmt->rowCount() > 0) $errors[] = "Username or mobile already exists.";

    // If no errors, proceed
    if (empty($errors)) {
        $publicKey = $_POST['stellar_public_key'];
        $secretKey = $_POST['stellar_secret_key'];

        // Encrypt secret key
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedSecret = openssl_encrypt($secretKey, 'aes-256-cbc', $encryption_key, 0, $iv);
        $encryptedWithIv = base64_encode($iv . $encryptedSecret);

        // Hash password
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert into users table
        $sql = "INSERT INTO users (stellarPublicKey, encryptedSecretKey, username, mobile, password,agentCode) 
                VALUES (:stellarPublicKey, :encryptedSecretKey, :username, :mobile, :password, :agentCode)";
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([
            ':stellarPublicKey' => $publicKey,
            ':encryptedSecretKey' => $encryptedWithIv,
            ':username' => $_POST['username'],
            ':mobile' => $_POST['mobile'],
            ':password' => $passwordHash,
			':agentCode' => $agentCode
        ]);

        if ($res) {
            $userId = $conn->lastInsertId();
            $meta = $conn->prepare("INSERT INTO user_info (uid, mobile) VALUES (?, ?)");
            $meta->execute([$userId, $_POST['mobile']]);
            $messages[] = "Registration successful!";
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Database error.";
        }
    }
}
?>


<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>stellarPay - stellarPay Management System</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

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
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
	
<!-- Include Stellar.js SDK -->
<script src="https://cdn.jsdelivr.net/npm/stellar-sdk@10.0.0/dist/stellar-sdk.min.js"></script>

<script>
  function generateStellarKeys() {
    const pair = StellarSdk.Keypair.random();
    document.getElementById('stellarPublicKey').value = pair.publicKey();
    document.getElementById('stellarSecretKey').value = pair.secret();
  }

  // Generate keys on page load
  window.onload = generateStellarKeys;
</script>

  </head>

  <body>
    <!-- Content -->

<div class="position-relative">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <!-- Register Card -->
      <div class="card p-2">
        <!-- Logo -->
        <div class="app-brand justify-content-center mt-5">
          <a href="index.html" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
              <img src="assets/img/logoWeb.png" alt="Logo" width="20" height="20" />
            </span>
            <span class="app-brand-text demo text-heading fw-semibold">stellarPay</span>
          </a>
        </div>
        <!-- /Logo -->

        <div class="card-body mt-2">
          <h4 class="mb-2 text-center">Jisajili sasa kuanza safari yako 🚀</h4>
          <p class="mb-4 text-center">Mfumo wa Usimamizi wa stellarPay</p>

          <?php
          if (!empty($errors) || !empty($messages)) {
              echo '<div class="alert ';
              if (!empty($errors)) {
                  echo 'alert-danger">';
                  foreach ($errors as $error) {
                      echo '<i class="fas fa-times"></i>&nbsp;' . $error . '<br>';
                  }
              }

              if (!empty($messages)) {
                  echo 'alert-success">';
                  foreach ($messages as $message) {
                      echo '<i class="fas fa-check"></i>&nbsp;' . $message . '<br>';
                  }
              }

              echo '</div>';
          }
          ?>

          <form id="formAuthentication" class="mb-3" action="" method="POST">
		      <!-- Hidden inputs for Stellar keys -->
             <input type="hidden" name="stellar_public_key" id="stellarPublicKey">
             <input type="hidden" name="stellar_secret_key" id="stellarSecretKey">

             <!-- CSRF Token -->
             <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="form-floating form-floating-outline mb-3">
              <input
                type="text"
                class="form-control"
                id="username"
                name="username"
                placeholder="Jina kamili"
                value="<?php if(isset($_POST['username'])){ echo $_POST['username']; } ?>"
                autofocus />
              <label for="username">Jina Kamili</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
              <input
                type="text"
                class="form-control"
                id="mobile"
                name="mobile"
                placeholder="Namba ya Simu"
                value="<?php if(isset($_POST['mobile'])){ echo $_POST['mobile']; } ?>" />
              <label for="mobile">Namba ya Simu</label>
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                  <input
                    type="password"
                    id="password"
                    class="form-control"
                    name="password"
                    placeholder="Nenosiri"
                    value=""
                    aria-describedby="password" />
                  <label for="password">Nenosiri</label>
                </div>
                <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
              </div>
            </div>

            <div class="mb-3 form-password-toggle">
              <div class="input-group input-group-merge">
                <div class="form-floating form-floating-outline">
                  <input
                    type="password"
                    id="password"
                    class="form-control"
                    name="passwordr"
                    placeholder="Rudia nenosiri"
                    value=""
                    aria-describedby="password" />
                  <label for="password">Rudia Nenosiri</label>
                </div>
                <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
              </div>
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" checked />
                <label class="form-check-label" for="terms-conditions">
                  Nakubaliana na
                  <a href="javascript:void(0);">sera ya faragha na masharti</a>
                </label>
              </div>
            </div>

            <button class="btn btn-primary d-grid w-100">Jisajili</button>
          </form>

          <p class="text-center">
            <span>Tayari una akaunti?</span>
            <a href="auth_login.php">
              <span>Ingia hapa</span>
            </a>
          </p>
        </div>
      </div>
      <!-- /Register Card -->
    </div>
  </div>
</div>


    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
