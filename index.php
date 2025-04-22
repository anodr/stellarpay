<?php ob_start(); ?>
<?php session_start(); ?>
<?php require_once("includes/DB.php"); ?>
<?php //require_once("../include/sessions.php"); ?> 
<?php require_once("includes/functions.php"); ?>
<?php Confirm_Login();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

 ?>

<!DOCTYPE html>

<head>
    <!-- Existing meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <!-- Enhanced Title -->
    <title>stellarPay | Mobile Money Wallet & Blockchain Payments Platform</title>

    <!-- Primary Meta Tags -->
    <meta name="description" content="stellarPay: Fast, low-cost mobile money solutions powered by Stellar blockchain. Send/receive funds, pay bills, and access financial services across Tanzania with crypto security.">
    <meta name="keywords" content="stellarPay, mobile money Tanzania, blockchain payments, Stellar blockchain wallet, low-cost remittances, financial inclusion Africa, crypto mobile wallet, cross-border payments, digital finance Tanzania">
    <meta name="author" content="Southern Star Technologies">
    <meta name="robots" content="index, follow">

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

    <!-- Geo & Regional Targeting -->
    <meta name="geo.region" content="TZ">
    <meta name="geo.placename" content="Dar es Salaam">
    <meta name="ICBM" content="-6.7924, 39.2083">

    <!-- Existing links and scripts -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <!-- ... rest of your existing head content ... -->
	
	<!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="assets/vendor/fonts/materialdesignicons.css" />
    
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>

    <!-- Styles for a clean look -->
    <style>
        .card {
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .progress {
            height: 20px;
        }

        .text-center {
            margin-top: 10px;
        }
    </style>

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd">

    <!-- iOS Support -->
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
	
	<script src="stellar-sdk/stellar-sdk.min.js"></script>
</head>


  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- App Brand -->
  <div class="app-brand demo">
    <a href="index.php" class="app-brand-link">
      <span class="app-brand-logo demo me-1">
        <img src="assets/img/logoWeb.png" width="20" height="20" alt="Logo">
      </span>
      <span class="app-brand-text demo menu-text fw-semibold ms-2">stellarPay</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="mdi mdi-menu d-xl-block align-middle mdi-20px"></i>
    </a>
  </div>
  
  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item active">
      <a href="index.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
        <div data-i18n="Dashboard">Dashboard</div>
      </a>
    </li>

    <!-- Operations -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Operations</span>
    </li>
    <li class="menu-item">
      <a href="transactions/pay.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-cash"></i>
        <div data-i18n="Cash In/Out">Pay</div>
      </a>
    </li>
	<li class="menu-item">
      <a href="transactions/withdraw.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-cash"></i>
        <div data-i18n="Cash In/Out">Withdraw</div>
      </a>
    </li>

    <!-- Transaction History -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Transaction History</span>
    </li>
    <li class="menu-item">
      <a href="history/transaction_history.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-history"></i>
        <div data-i18n="Transaction History">View Transactions</div>
      </a>
    </li>

    <!-- Support Section -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Support</span>
    </li>
    <li class="menu-item">
      <a href="support/contact.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-help-circle-outline"></i>
        <div data-i18n="Contact Support">Contact Support</div>
      </a>
    </li>

    <!-- Settings Section -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Settings</span>
    </li>
    <li class="menu-item">
      <a href="#settings/account.php" class="menu-link">
        <i class="menu-icon tf-icons mdi mdi-cog-outline"></i>
        <div data-i18n="Account Settings">Account Settings</div>
      </a>
    </li>
  </ul>
</aside>


        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="mdi mdi-menu mdi-24px"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="mdi mdi-magnify mdi-24px lh-0"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none bg-body"
                    placeholder="Search..."
                    aria-label="Search..." />
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <?php 
		             if(isset($_SESSION['login'])){
		              $ID = $_SESSION['id'];
				       }
                     require_once('includes/DB.php');
                     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                     $res1 = $conn->prepare("SELECT username as Username, stellarPublicKey AS publicKey FROM users WHERE id='$ID'");
                     $res1->execute();
	                 $row = $res1->fetch(PDO::FETCH_ASSOC);
					 $Username = ($row['Username']);
					 $publicKey = ($row['publicKey']);
	                ?>
                <li class="nav-item lh-1 me-3">
                  <a
                    class="github-button"
                    href=""
                    data-icon="octicon-star"
                    data-size="large"
                    data-show-count="true"
                    aria-label="Star themeselection/materio-bootstrap-html-admin-template-free on GitHub"
                    ><?php echo $Username; ?></a>
                </li>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="assets/img/logoWeb.png" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                    <li>
                      <a class="dropdown-item pb-2 mb-1" href="#">
                        <div class="d-flex align-items-center">
                          <div class="flex-shrink-0 me-2 pe-1">
                            <div class="avatar avatar-online">
                              <img src="assets/img/logoWeb.png" alt="" class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0"><?php echo $Username; ?></h6>
                            <small class="text-muted"></small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="mdi mdi-account-outline me-1 mdi-20px"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="mdi mdi-cog-outline me-1 mdi-20px"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="logout.php">
                        <i class="mdi mdi-power me-1 mdi-20px"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->
		  
<?php include_once("dashboard/earnings.php"); ?>	
<?php //include_once("dashboard_ai/response_time.php"); ?>		  
<?php //include_once("dashboard_ai/subjects_performance.php"); ?>
<?php //include_once("dashboard_ai/adaptive_learning.php"); ?>	
<?php //include_once("dashboard_ai/subjects_performance.php"); ?>
<?php //include_once("dashboard_ai/subjects_performance.php"); ?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row gy-4">
			  
<div>
    <?php 
    if(isset($_SESSION['SuccessMessage'])){
        echo SuccessMessage();
        unset($_SESSION["SuccessMessage"]);
        echo "<script>
                // Wait for 3 seconds and then reload the page
                setTimeout(function(){
                    location.reload();
                }, 4000);
            </script>";
    }
    ?>
</div>


<div class="container mt-3">
    <h2 class="text-center mb-3"><?php echo $Username; ?></h2>
	
<div class="row">

    <!-- Deposit Card -->
    <a href="transactions/pay.php" class="col-md-4 col-xl-4 mb-4 text-decoration-none">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <i class="menu-icon tf-icons mdi mdi-cash-plus me-2"></i>Make Payment
            </div>
            <div class="card-body">
                <p class="mb-0">Make payment for goods/services</p>
            </div>
        </div>
    </a>

    <!-- Withdraw Card -->
    <a href="transactions/withdraw.php" class="col-md-4 col-xl-4 mb-4 text-decoration-none">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <i class="menu-icon tf-icons mdi mdi-cash-minus me-2"></i>Withdraw
            </div>
            <div class="card-body">
                <p class="mb-0">Withdraw funds from your account</p>
            </div>
        </div>
    </a>
	
<!-- Fund Card -->
<div class="col-md-4 col-xl-4 mb-4 text-decoration-none" onclick="fundWithFriendbot()" style="cursor: pointer;">
  <div class="card border-primary">
    <div class="card-header bg-primary text-white">
      <i class="menu-icon tf-icons mdi mdi-cash-minus me-2"></i>
      Fund Account
    </div>
    <div class="card-body">
      <p class="mb-0">Fund Account using FRIENDBOT</p>
    </div>
  </div>
</div>




</div>



<script>
async function fundWithFriendbot() {
  const publicKey = "<?php echo $publicKey; ?>";

  if (!publicKey || publicKey.length !== 56) {
    alert("⚠️ Invalid public key.");
    return;
  }

  try {
    const response = await fetch(`https://friendbot.stellar.org/?addr=${encodeURIComponent(publicKey)}`);

    // Check if the response was successful
    if (!response.ok) {
      const errorData = await response.json();
      if (errorData.detail?.includes("already funded")) {
        alert("✅ Account already funded.");
      } else {
        alert(`❌ Friendbot Error: ${errorData.detail || "Unknown issue"}`);
        console.error("Friendbot error response:", errorData);
      }
      return;
    }

    // Success
    const data = await response.json();
    if (data.hash) {
      alert("✅ Account funded successfully!");
      console.log("Friendbot response:", data);
      checkBalance(publicKey); // Optional: refresh balance
    } else {
      alert("⚠️ Unexpected response from Friendbot.");
      console.log("Unexpected Friendbot response:", data);
    }

  } catch (error) {
    alert("❌ Network or unexpected error occurred.");
    console.error("Fetch failed:", error);
  }
}

</script>


<br>


<div class="row">

<h2 class="text-center mb-3"><?php echo $Username; ?> Dashboard</h2>

<hr>

    <!-- Exchange Rate Warning -->
    <?php if(!$exchangeRate['xlmToTsh']): ?>
    <div class="col-12">
        <div class="alert alert-warning">
            <i class="menu-icon tf-icons mdi mdi-alert-circle-outline me-2"></i>
            Live exchange rates unavailable. Using last known rate for TZS conversions.
        </div>
    </div>
    <?php endif; ?>

    <!-- Commission Cards -->
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card border-primary">
            <div class="card-header text-black">
                <i class="menu-icon tf-icons mdi mdi-calendar-week me-2"></i>Today's Commission
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h5 mb-0"><?= number_format($commissions['today']['xlm'], 7) ?> XLM</div>
                        <small class="text-muted">Cryptocurrency Amount</small>
                    </div>
                    <div class="text-end">
                        <div class="h5 mb-0"><?= $commissions['today']['tsh'] ? number_format($commissions['today']['tsh'], 0) : 'N/A' ?> TZS</div>
                        <small class="text-muted">Local Value</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card border-primary">
            <div class="card-header  text-black">
                <i class="menu-icon tf-icons mdi mdi-calendar-week me-2"></i>Weekly Commission
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h5 mb-0"><?= number_format($commissions['week']['xlm'], 7) ?> XLM</div>
                        <small class="text-muted">Cryptocurrency Amount</small>
                    </div>
                    <div class="text-end">
                        <div class="h5 mb-0"><?= $commissions['week']['tsh'] ? number_format($commissions['week']['tsh'], 0) : 'N/A' ?> TZS</div>
                        <small class="text-muted">Local Value</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card border-primary">
            <div class="card-header  text-black">
                <i class="menu-icon tf-icons mdi mdi-calendar-month me-2"></i>Monthly Commission
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h5 mb-0"><?= number_format($commissions['month']['xlm'], 7) ?> XLM</div>
                        <small class="text-muted">Cryptocurrency Amount</small>
                    </div>
                    <div class="text-end">
                        <div class="h5 mb-0"><?= $commissions['month']['tsh'] ? number_format($commissions['month']['tsh'], 0) : 'N/A' ?> TZS</div>
                        <small class="text-muted">Local Value</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card border-primary">
            <div class="card-header  text-black">
                <i class="menu-icon tf-icons mdi mdi-calendar-month me-2"></i>Yearly Commission
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h5 mb-0"><?= number_format($commissions['year']['xlm'], 7) ?> XLM</div>
                        <small class="text-muted">Cryptocurrency Amount</small>
                    </div>
                    <div class="text-end">
                        <div class="h5 mb-0"><?= $commissions['year']['tsh'] ? number_format($commissions['year']['tsh'], 0) : 'N/A' ?> TZS</div>
                        <small class="text-muted">Local Value</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card border-primary">
            <div class="card-header  text-black">
                <i class="menu-icon tf-icons mdi mdi-chart-line me-2"></i>All-Time Commission
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="h5 mb-0"><?= number_format($commissions['all']['xlm'], 7) ?> XLM</div>
                        <small class="text-muted">Cryptocurrency Amount</small>
                    </div>
                    <div class="text-end">
                        <div class="h5 mb-0"><?= $commissions['all']['tsh'] ? number_format($commissions['all']['tsh'], 0) : 'N/A' ?> TZS</div>
                        <small class="text-muted">Local Value</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</div>
				
                <!--/ Data Tables -->
              </div>
            </div>
            <!-- / Content -->

            <?php require_once("includes/footer.php"); ?>

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

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
    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
	
	<!-- Page level plugins -->
  <script src="assets/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/datatables/dataTables.bootstrap5.min.js"></script>
  
<script>
    $(document).ready(function() {
        $('#dataTable, #dataTable2, #dataTable3').DataTable();
    });
</script>


<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js')
      .then(reg => console.log("Service Worker registered:", reg))
      .catch(err => console.error("Service Worker failed:", err));
  }
</script>



  </body>
</html>
