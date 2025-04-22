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
              <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="../index.php">Home</a> / Account /</span> Deposit Fund to Customer Account</h4>

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
    <p class="card-header">Deposit Fund to Customer Account</p>
    <div class="card-body">
      <div class="form-floating mb-2">
        <input type="text" id="agentCode" class="form-control" />
        <label for="agentCode">Customer Stellar Code</label>
      </div>
	  
	  <div class="form-floating form-floating-outline mb-2">
       <input type="text" class="form-control" id="customerPublicKey" placeholder="Stellar Public Key" readonly  disabled />
       <label for="customerPublicKey">Customer Stellar Public Key</label>
      </div>

	  
      <div class="form-floating mb-2">
        <input type="number" id="amountDeposit" class="form-control" placeholder="Amount" />
        <label for="amountDeposit">Enter Amount (Tsh)</label>
      </div>

      <!-- Equivalent XLM -->
      <div class="form-floating form-floating-outline mb-2">
        <input type="text" class="form-control" id="equivalentXlm" placeholder="Equivalent XLM" readonly  disabled />
        <label for="equivalentXlm">Equivalent Amount in XLM</label>
      </div>

      <!-- Customer Name (optional, display only) -->
      <div class="form-floating form-floating-outline mb-2">
        <input type="text" class="form-control" id="customerName" placeholder="Customer Name" readonly disabled />
        <label for="customerName">Customer Name</label>
      </div>

      <div>
        <button type="button" class="btn btn-primary form-control" onclick="sendToAgent()">Deposit</button>
      </div>
    </div>
  </div>
</div>



<script>
document.getElementById('agentCode').addEventListener('input', async () => {
  const code = document.getElementById('agentCode').value.trim();

  if (code.length > 0) {
    const res = await fetch(`getCustomer.php?agentCode=${code}`);
    const data = await res.json();

    document.getElementById('customerName').value = data?.username || 'Not found';
    document.getElementById('customerPublicKey').value = data?.stellarPublicKey || '';
  } else {
    document.getElementById('customerName').value = '';
    document.getElementById('customerPublicKey').value = '';
  }
});



// Fetch XLM to USD price
async function fetchXLMtoUSD() {
  const response = await fetch('https://api.coingecko.com/api/v3/simple/price?ids=stellar&vs_currencies=usd');
  const data = await response.json();
  return data.stellar.usd;
}

// Static USD to TZS rate
const usdToTZS = 2500;

// Format number nicely
function formatNumberWithCommas(number) {
  return new Intl.NumberFormat().format(number);
}

// Calculate equivalent XLM from Tsh
async function calculateWithdrawTZStoXLM() {
  const amountTzs = parseFloat(document.getElementById('amountDeposit').value);
  if (amountTzs > 0) {
    const xlmToUSD = await fetchXLMtoUSD();
    const amountUsd = amountTzs / usdToTZS;
    const equivalentXlm = amountUsd / xlmToUSD;
    document.getElementById('equivalentXlm').value = formatNumberWithCommas(equivalentXlm.toFixed(2));
  } else {
    document.getElementById('equivalentXlm').value = '';
  }
}

// Attach event listener
document.getElementById('amountDeposit').addEventListener('input', calculateWithdrawTZStoXLM);

  function sendToAgent() {
    const customerCode = document.getElementById('agentCode').value.trim();
    const amount = parseFloat(document.getElementById('equivalentXlm').value.replace(/,/g, ''));

    if (customerCode && amount > 0) {
      alert(`Would deposit ${amount} XLM to ${customerCode}`);
      
      // TODO: Implement actual Stellar transaction here

      // Clear the form after transaction
      clearDepositForm();
    } else {
      alert("Please enter a valid Stellar Code and amount.");
    }
  }

  function clearDepositForm() {
    document.getElementById('agentCode').value = '';
    document.getElementById('customerPublicKey').value = '';
    document.getElementById('amountDeposit').value = '';
    document.getElementById('equivalentXlm').value = '';
    document.getElementById('customerName').value = '';
  }
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/stellar-sdk/10.4.0/stellar-sdk.min.js"></script>

<script>
const sourceSecret = "SC5C4ETSVPG4SGMZ4Y2XFWKQIVIOBXSS24Z6PN67JGAXOJYEW77BLSPC"; // Testnet secret key

async function sendToAgent() {
  const destination = document.getElementById('customerPublicKey').value.trim();
  const amount = document.getElementById('equivalentXlm').value.trim();

  // 🐛 DEBUG: Show destination and amount in console
  console.log("Destination (Public Key):", destination);
  console.log("Amount (XLM):", amount);

  if (!destination || !amount) {
    alert("⚠️ Tafadhali jaza Agent Code na Kiasi.");
    return;
  }

  if (isNaN(amount) || parseFloat(amount) <= 0) {
    alert("⚠️ Kiasi cha XLM lazima kiwe namba na kiwe kikubwa kuliko sifuri.");
    return;
  }

  alert("✅ Valid input. Now sending...");
  await sendXLM(destination, amount);
}

async function sendXLM(destination, amount) {
  const { Server, Keypair, Networks, TransactionBuilder, Operation, Asset } = StellarSdk;

  const server = new Server('https://horizon-testnet.stellar.org');
  const sourceKeypair = Keypair.fromSecret(sourceSecret);
  const sourcePublic = sourceKeypair.publicKey();

  try {
    // 🐛 DEBUG: Check if destination is a valid length
    console.log("Checking if destination is valid length:", destination.length); // should be 56

    try {
      await server.loadAccount(destination);
    } catch (e) {
      alert("❌ Akaunti ya mpokeaji haipo kwenye Stellar Testnet.");
      console.error("Account Load Error:", e);
      return;
    }

    const account = await server.loadAccount(sourcePublic);
    const fee = await server.fetchBaseFee();

    const transaction = new TransactionBuilder(account, {
      fee,
      networkPassphrase: Networks.TESTNET
    })
    .addOperation(Operation.payment({
      destination,
      asset: Asset.native(),
      amount
    }))
    .setTimeout(30)
    .build();

    transaction.sign(sourceKeypair);

    const result = await server.submitTransaction(transaction);
    alert("✅ Malipo yamefanikiwa! Hash: " + result.hash);
    console.log("Transaction success:", result);

    // Send transaction data to your PHP backend
    const response = await fetch('save_transaction.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        tx_hash: result.hash,
        amount: amount,
        destination: destination,
        source: sourcePublic,
        status: 'success'
      })
    });

    const responseData = await response.json();
    if (responseData.success) {
      console.log("Transaction data saved to the database.");
    } else {
      console.log("Error saving transaction data.");
    }
  } catch (err) {
    console.error("Transaction failed", err?.response?.data || err.message || err);
    //alert("❌ Malipo yameshindwa. Angalia console kwa maelezo.");
  }
}
</script>


<div><hr></div>
								
				
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
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Asset</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Link</th>
            </tr>
          </thead>
          <tbody>
            <?php
            try {
              require_once('../includes/DB.php');

              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              if (isset($_SESSION['username'])) {
                  $loggedInUser = $_SESSION['username'];

                  $stmt = $conn->prepare("SELECT * FROM transactions WHERE user = :user ORDER BY created_at DESC");
                  $stmt->bindParam(':user', $loggedInUser, PDO::PARAM_STR);
                  $stmt->execute();

                  $SrNo = 0;

                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $SrNo++;
                    $from = $row['source'];
                    $to = $row['destination'];
                    $amount = number_format($row['amount'], 7);
                    $user = $row['user'];
                    $date = $row['created_at'];
                    $txHash = $row['tx_hash'];
            ?>
              <tr>
                <td class="align-middle text-sm"><?php echo $SrNo; ?></td>
                <td class="align-middle text-sm"><?php echo htmlspecialchars($from) . " ➡️ " . htmlspecialchars($to); ?></td>
                <td class="align-middle text-sm"><?php echo $amount; ?></td>
                <td class="align-middle text-sm"><?php echo htmlspecialchars($user); ?></td>
                <td class="align-middle text-sm">XLM</td>
                <td class="align-middle text-sm"><?php echo $date; ?></td>
                <td class="align-middle text-sm">
                  <a href="https://stellar.expert/explorer/public/tx/<?php echo $txHash; ?>" target="_blank">View</a>
                </td>
              </tr>
            <?php
                  }
              } else {
                  echo "<tr><td colspan='7'>User not logged in.</td></tr>";
              }
            } catch (Exception $e) {
              echo "<tr><td colspan='7'>Error: " . $e->getMessage() . "</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!--/ Hoverable Table rows -->
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
