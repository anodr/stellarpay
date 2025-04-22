<?php ob_start(); ?>
<?php session_start(); ?>
<?php require_once("../includes/DB.php"); ?>
<?php //require_once("../include/sessions.php"); ?> 
<?php require_once("../includes/functions.php"); ?>
<?php ConfirmFrontPage_Login() ?>

<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
	<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">

    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Fund Stellar Account - Fund Stellar Management System</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/materialdesignicons.css" />
	
	<!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="../assets/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
	
	<script src="../stellar-sdk/stellar-sdk.min.js"></script>

  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
		
		<?php require_once("../includes/aside.php"); ?>

        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <?php require_once("../includes/nav.php"); ?>

          <!-- Content wrapper -->
          <div class="content-wrapper">
		  
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="../index.php">Home</a> / History /</span> Transaction History</h4>

<div class="row">
  <!-- Hoverable Table rows -->
  <div class="card">
    <h5 class="card-header">Transaction History</h5>
    <div class="card-body">                
      <div class="table-responsive">
        <table class="table align-items-center mb-0" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">From ➡️ To</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fees</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Details</th>
            </tr>
          </thead>
          <tbody>
            <?php
            try {
                require_once('../includes/DB.php');
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Get exchange rates
                $xlmToTsh = null;
                $response = @file_get_contents('https://api.coingecko.com/api/v3/simple/price?ids=stellar&vs_currencies=usd');
                $usdToTsh = 2500; // Static USD-TZS rate

                if ($response) {
                    $data = json_decode($response, true);
                    if (isset($data['stellar']['usd'])) {
                        $xlmToUsd = $data['stellar']['usd'];
                        $xlmToTsh = $xlmToUsd * $usdToTsh;
                    }
                }

                if (!isset($_SESSION['login'])) {
                    echo '<tr><td colspan="6" class="text-center">Please login to view transactions</td></tr>';
                } else {
                    $loggedInUser = $_SESSION['username'];
                    $stmt = $conn->prepare("
                        SELECT 
                            tx_hash,
                            source_account,
                            destination,
                            amount_xlm,
                            net_amount_xlm,
                            agent_fee_xlm,
                            system_fee_xlm,
                            created_at
                        FROM transactions 
                        WHERE user = :user 
                        ORDER BY created_at DESC
                    ");
                    $stmt->bindValue(':user', $loggedInUser, PDO::PARAM_STR);
                    
                    if ($stmt->execute()) {
                        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $transactionsFound = false;

                        foreach ($transactions as $index => $row) {
                            $transactionsFound = true;
                            
                            // Convert amounts to TZS if rate available
                            $amountTsh = $xlmToTsh ? number_format($row['amount_xlm'] * $xlmToTsh, 0) : 'N/A';
                            $agentFeeTsh = $xlmToTsh ? number_format($row['agent_fee_xlm'] * $xlmToTsh, 0) : 'N/A';
                            $systemFeeTsh = $xlmToTsh ? number_format($row['system_fee_xlm'] * $xlmToTsh, 0) : 'N/A';
                            
                            echo '
                            <tr>
                                <td class="align-middle text-sm">'.($index + 1).'</td>
                                <td class="align-middle text-sm">
                                    <div class="d-flex flex-column">
                                        <span class="text-xs">From: '.htmlspecialchars($row['source_account']).'</span>
                                        <span class="text-xs">To: '.htmlspecialchars($row['destination']).'</span>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <div class="d-flex flex-column">
                                        <span class="text-success">'.number_format($row['amount_xlm'], 7).' XLM</span>
                                        <span class="text-primary">≈ TSh '.$amountTsh.'</span>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">
                                    <div class="d-flex flex-column">
                                        <div class="mb-1">
                                            <span class="text-xs">Agent Fee: </span>
                                            <span class="text-danger">'.$row['agent_fee_xlm'].' XLM</span>
                                            <br>
                                            <span class="text-xs">≈ TSh '.$agentFeeTsh.'</span>
                                        </div>
                                        <div>
                                            <span class="text-xs">System Fee: </span>
                                            <span class="text-danger">'.$row['system_fee_xlm'].' XLM</span>
                                            <br>
                                            <span class="text-xs">≈ TSh '.$systemFeeTsh.'</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-sm">'
                                    .date('M d, Y H:i', strtotime($row['created_at'])).
                                '</td>
                                <td class="align-middle text-sm">
                                    <a href="https://stellar.expert/explorer/public/tx/'
                                    .htmlspecialchars($row['tx_hash']).'" 
                                    target="_blank" 
                                    class="btn btn-sm btn-outline-primary">
                                        <i class="menu-icon tf-icons mdi mdi-alert-circle-outline"></i>
                                    </a>
                                </td>
                            </tr>';
                        }

                        if (!$transactionsFound) {
                            echo '<tr><td colspan="6" class="text-center">No transactions found</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">Error loading transactions</td></tr>';
                    }
                }
            } catch (PDOException $e) {
                error_log("Database Error: " . $e->getMessage());
                echo '<tr><td colspan="6" class="text-center">Error loading transaction history</td></tr>';
            } catch (Exception $e) {
                error_log("General Error: " . $e->getMessage());
                echo '<tr><td colspan="6" class="text-center">System error occurred</td></tr>';
            }
            ?>
          </tbody>
        </table>
        <?php if(!$xlmToTsh): ?>
        <div class="alert alert-warning mt-3">
            <i class="fas fa-exclamation-triangle"></i> 
            Currency conversion rates unavailable - displaying XLM values only
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

            <!-- Footer -->
            <?php require_once("../includes/footer.php"); ?>
            <!-- / Footer -->

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
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <script src="../assets/js/form-basic-inputs.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
	
	
<!-- Page level plugins -->
  <script src="../assets/datatables/jquery.dataTables.min.js"></script>
  <script src="../assets/datatables/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable, #dataTable2, #dataTable3').DataTable();
    });
</script>



	
  </body>
</html>
