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
              <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="../index.php">Home</a> / Account /</span> Withdraw Fund From stellarPesa Agent</h4>

              <div class="row">
			  
<div>
    <?php 
    if(isset($_SESSION['SuccessMessage'])){
        echo SuccessMessage();
        unset($_SESSION["SuccessMessage"]);
        echo "<script>
                // Wait for 3 seconds and then reload the page
                setTimeout(function(){
                    location.reload();
                }, 2000);
            </script>";
    }
    ?>
</div>



<div class="col-md-6 col-lg-6">
  <div class="card mb-4">
    <p class="card-header">Withdraw Fund from stellarPay Agent</p>
    <div class="card-body">
      <!-- Form elements remain unchanged -->
      <div class="form-floating mb-2">
        <input type="text" id="mobile" class="form-control" />
        <label for="mobile">stellarPay Agent Code</label>
      </div>
      
      <div class="form-floating form-floating-outline mb-2">
        <input type="text" class="form-control" id="agentPublicKey" placeholder="Stellar Public Key" readonly disabled />
        <label for="agentPublicKey">stellarPay Agent Public Key</label>
      </div>
      
      <div class="form-floating mb-2">
        <input type="number" id="amount" class="form-control" placeholder="Amount" />
        <label for="amount">Enter Amount (Tsh)</label>
      </div>

      <div class="form-floating form-floating-outline mb-2">
        <input type="text" class="form-control" id="equivalentXlm" placeholder="Equivalent XLM" readonly disabled />
        <label for="equivalentXlm">Equivalent Amount in XLM</label>
      </div>

      <div class="form-floating form-floating-outline mb-2">
        <input type="text" class="form-control" id="agentName" placeholder="stellarPay Agent Name" readonly disabled />
        <label for="agentName">stellarPay Agent Name</label>
      </div>

      <div>
        <button type="button" class="btn btn-primary form-control" onclick="sendToAgent()">Withdraw</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/stellar-sdk/10.0.0/stellar-sdk.min.js"></script>

<script>
// Configuration (INSECURE - FOR DEMO ONLY)
const sourceSecret = "SC5C4ETSVPG4SGMZ4Y2XFWKQIVIOBXSS24Z6PN67JGAXOJYEW77BLSPC";
const yourPublicKey = "GCXZAGVD65CAOPKZTKPY3JQ7JMKW4RF7A3BIHSYO6P5HPU2DID6M3JU4";

// Form initialization
document.getElementById('mobile').addEventListener('input', async (e) => {
  const code = e.target.value.trim();
  if (code.length > 0) {
    try {
      const res = await fetch(`getCustomer.php?mobile=${code}`);
      const data = await res.json();
      document.getElementById('agentName').value = data?.username || 'Not found';
      document.getElementById('agentPublicKey').value = data?.stellarPublicKey || '';
    } catch (error) {
      console.error('Agent lookup failed:', error);
      document.getElementById('agentName').value = 'Error loading agent';
    }
  } else {
    document.getElementById('agentName').value = '';
    document.getElementById('agentPublicKey').value = '';
  }
});

document.getElementById('amount').addEventListener('input', calculateWithdrawTZStoXLM);

// Currency conversion functions
async function calculateWithdrawTZStoXLM() {
  const amountTzs = parseFloat(document.getElementById('amount').value);
  const equivalentXlmEl = document.getElementById('equivalentXlm');
  
  if (amountTzs > 0) {
    try {
      const xlmToUSD = await fetchXLMtoUSD();
      const amountUsd = amountTzs / 2500; // TZS to USD
      const equivalentXlm = (amountUsd / xlmToUSD).toFixed(7);
      equivalentXlmEl.value = equivalentXlm;
    } catch (error) {
      console.error('Conversion error:', error);
      equivalentXlmEl.value = 'Error';
    }
  } else {
    equivalentXlmEl.value = '';
  }
}

async function fetchXLMtoUSD() {
  try {
    const response = await fetch('https://api.coingecko.com/api/v3/simple/price?ids=stellar&vs_currencies=usd');
    const data = await response.json();
    return data.stellar.usd;
  } catch (error) {
    console.error('Failed to fetch XLM price:', error);
    throw new Error('Could not get XLM exchange rate');
  }
}

// Transaction handling
async function sendToAgent() {
  try {
    // Validate inputs
    const mobile = document.getElementById('mobile').value.trim();
    const amountTzs = parseFloat(document.getElementById('amount').value);
    const agentPublicKey = document.getElementById('agentPublicKey').value;
    const equivalentXlm = parseFloat(document.getElementById('equivalentXlm').value.replace(/,/g, ''));

    if (!mobile || !amountTzs || !agentPublicKey || isNaN(equivalentXlm)) {
      throw new Error('Please fill all fields correctly');
    }

    // Calculate amounts
    const platformFee = (equivalentXlm * 0.01).toFixed(7);
    const agentFee = (equivalentXlm * 0.01).toFixed(7);
    const netAmount = (equivalentXlm - platformFee - agentFee).toFixed(7);

    // Execute transaction
    const result = await sendXLM(agentPublicKey, netAmount, agentFee, platformFee);

    // Save transaction record (FIXED)
    const saveResponse = await fetch('save_transaction.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        tx_hash: result.hash,
        amount_xlm: equivalentXlm.toFixed(7),
        net_amount_xlm: netAmount,
        agent_fee_xlm: agentFee,
        system_fee_xlm: platformFee,
        destination: agentPublicKey,
        agent_public_key: agentPublicKey,
        source_account: yourPublicKey, // Fixed parameter name
        status: 'success'
      })
    });

    if (!saveResponse.ok) {
      const errorData = await saveResponse.json();
      throw new Error(errorData.message || 'Failed to save transaction record');
    }

    // Clear form and show success
    document.getElementById('mobile').value = '';
    document.getElementById('amount').value = '';
    document.getElementById('equivalentXlm').value = '';
    alert(`Success! ${netAmount} XLM sent\nTX Hash: ${result.hash}`);

  } catch (error) {
    console.error("Withdrawal failed:", error);
    alert(`Error: ${error.message}`);
  }
}

async function sendXLM(destinationPublicKey, netAmount, agentFee, platformFee) {
  const { Server, Keypair, TransactionBuilder, Networks, Operation } = StellarSdk;
  const server = new Server('https://horizon-testnet.stellar.org');
  const sourceKeypair = Keypair.fromSecret(sourceSecret);

  try {
    // Load accounts
    const sourceAccount = await server.loadAccount(sourceKeypair.publicKey());
    const baseFee = await server.fetchBaseFee();

    // Build transaction
    const transaction = new TransactionBuilder(sourceAccount, {
      fee: baseFee,
      networkPassphrase: Networks.TESTNET
    })
      .addOperation(Operation.payment({
        destination: destinationPublicKey,
        asset: StellarSdk.Asset.native(),
        amount: netAmount.toString()
      }))
      .addOperation(Operation.payment({
        destination: destinationPublicKey,
        asset: StellarSdk.Asset.native(),
        amount: agentFee.toString()
      }))
      .addOperation(Operation.payment({
        destination: yourPublicKey,
        asset: StellarSdk.Asset.native(),
        amount: platformFee.toString()
      }))
      .setTimeout(30)
      .build();

    // Sign and submit
    transaction.sign(sourceKeypair);
    return await server.submitTransaction(transaction);

  } catch (error) {
    console.error("Transaction error:", error);
    const errorMsg = error.response?.data?.extras?.result_codes || error.message;
    throw new Error(`Stellar transaction failed: ${errorMsg}`);
  }
}
</script>

<div><hr></div>
								
				



<div class="row">
  <div class="card">
    <h5 class="card-header">Transaction History</h5>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table align-items-center mb-0 DataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>From ➡️ To</th>
              <th>Amount</th>
              <th>Fees</th>
              <th>Date</th>
              <th>Details</th>
            </tr>
          </thead>
          <tbody>
            <?php
            try {
              require_once('../includes/DB.php');
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              // Fetch XLM to TZS rate
              $xlmToTsh = null;
              $usdToTsh = 2500; // Static fallback rate

              $response = @file_get_contents('https://api.coingecko.com/api/v3/simple/price?ids=stellar&vs_currencies=usd');
              if ($response) {
                $data = json_decode($response, true);
                if (isset($data['stellar']['usd'])) {
                  $xlmToTsh = $data['stellar']['usd'] * $usdToTsh;
                }
              }

              if (!isset($_SESSION['login'])) {
                echo '<tr><td colspan="6" class="text-center text-warning">Please login to view transactions.</td></tr>';
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
                $stmt->execute();
                $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($transactions) === 0) {
                  echo '<tr><td colspan="6" class="text-center text-muted">No transactions found.</td></tr>';
                } else {
                  foreach ($transactions as $index => $row) {
                    $amountTsh = $xlmToTsh ? number_format($row['amount_xlm'] * $xlmToTsh, 0) : 'N/A';
                    $agentFeeTsh = $xlmToTsh ? number_format($row['agent_fee_xlm'] * $xlmToTsh, 0) : 'N/A';
                    $systemFeeTsh = $xlmToTsh ? number_format($row['system_fee_xlm'] * $xlmToTsh, 0) : 'N/A';

                    echo '<tr>
                      <td>' . ($index + 1) . '</td>
                      <td>
                        <div>From: ' . htmlspecialchars($row['source_account']) . '</div>
                        <div>To: ' . htmlspecialchars($row['destination']) . '</div>
                      </td>
                      <td>
                        <div>' . number_format($row['amount_xlm'], 7) . ' XLM</div>
                        <div>≈ TSh ' . $amountTsh . '</div>
                      </td>
                      <td>
                        <div>Agent Fee: ' . $row['agent_fee_xlm'] . ' XLM<br>≈ TSh ' . $agentFeeTsh . '</div>
                        <div>System Fee: ' . $row['system_fee_xlm'] . ' XLM<br>≈ TSh ' . $systemFeeTsh . '</div>
                      </td>
                      <td>' . date('M d, Y H:i', strtotime($row['created_at'])) . '</td>
                      <td>
                        <a href="https://stellar.expert/explorer/public/tx/' . htmlspecialchars($row['tx_hash']) . '" 
                          target="_blank" 
                          class="btn btn-sm btn-outline-primary">
                          View
                        </a>
                      </td>
                    </tr>';
                  }
                }
              }
            } catch (PDOException $e) {
              error_log("DB Error: " . $e->getMessage());
              echo '<tr><td colspan="6" class="text-center text-danger">Error loading transaction history.</td></tr>';
            } catch (Exception $e) {
              error_log("General Error: " . $e->getMessage());
              echo '<tr><td colspan="6" class="text-center text-danger">System error occurred.</td></tr>';
            }
            ?>
          </tbody>
        </table>

        <?php if (!$xlmToTsh): ?>
        <div class="alert alert-warning mt-3">
          <i class="fas fa-exclamation-triangle"></i>
          Currency conversion unavailable. Showing XLM only.
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>




















            </div>
            <!-- / Content -->

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


    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
	
<!-- DataTables CSS (CDN) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables JS (CDN) -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('.datatable').DataTable();
});
</script>



	
  </body>
</html>