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
              <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="../index.php">Home</a> / Account /</span> Fund Account</h4>

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

<?php 
if (isset($_SESSION['login'])) {
        $ID = $_SESSION['username'];
                    }
					
require_once("../includes/DB.php");

$stmt = $conn->prepare("SELECT stellarPublicKey FROM users WHERE username = ?");
$stmt->execute([$ID]);

if ($stmt->rowCount() == 0) {
    $_SESSION["ErrorMessage"] = "Invalid Code!";
    header("location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$agentRow = $stmt->fetch(PDO::FETCH_ASSOC);
$stellarAddress = $agentRow['stellarPublicKey'];

?>

<!-- UI -->
<div class="col-md-6 col-lg-6">
  <div class="card mb-4">
    <p class="card-header">Fund your Account</p>
    <div class="card-body">
      <div class="form-floating mb-2">
        <input type="text" id="agentCode" class="form-control" value="<?php echo $stellarAddress; ?>" />
        <label for="agentCode">Stellar Code</label>
      </div>
      <div class="form-floating mb-2">
        <input type="number" id="xlmAmount" class="form-control" placeholder="Amount" />
        <label for="xlmAmount">Enter XLM Amount</label>
      </div>
      <div>
        <button type="button" class="btn btn-primary form-control" onclick="sendToAgent()">Tuma XLM</button>
      </div>
    </div>
  </div>
</div>


<!-- Include this line at the top before your script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/stellar-sdk/10.4.0/stellar-sdk.min.js"></script>

<script>
const sourceSecret = "SC5C4ETSVPG4SGMZ4Y2XFWKQIVIOBXSS24Z6PN67JGAXOJYEW77BLSPC"; // Testnet secret key

async function sendToAgent() {
  alert("🔍 sendToAgent function is triggered!");

  const destination = document.getElementById('agentCode').value.trim();
  const amount = document.getElementById('xlmAmount').value.trim();

  if (!destination || !amount) {
    alert("⚠️ Tafadhali jaza Agent Code na Kiasi.");
    return;
  }

  alert("✅ Valid input. Now sending...");
  await sendXLM(destination, amount);
}

async function sendXLM(destination, amount) {
  const {
    Server,
    Keypair,
    Networks,
    TransactionBuilder,
    Operation,
    Asset
  } = StellarSdk;

  const server = new Server('https://horizon-testnet.stellar.org');
  const sourceKeypair = Keypair.fromSecret(sourceSecret);
  const sourcePublic = sourceKeypair.publicKey();

  try {
    // Check if destination account exists
    try {
      await server.loadAccount(destination);
    } catch (e) {
      alert("❌ Akaunti ya mpokeaji haipo kwenye Stellar Testnet.");
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
  } catch (err) {
    console.error("Transaction failed", err?.response?.data || err.message || err);
    alert("❌ Malipo yameshindwa. Angalia console kwa maelezo.");
  }
}
</script>


				
				
				
				<div><hr></div>
				
			<div class="row">
				  <!-- Hoverable Table rows -->


<div class="card">
    <h5 class="card-header"></h5>

<div class="card-body">				
    <div class="table-responsive">
        <table class="table align-items-center mb-0" id="dataTable" width="100%" cellspacing="0">
            <thead>
    <tr>
        <th>No</th>
        <th>From ➡️ To</th>
        <th>Amount</th>
        <th>Asset</th>
        <th>Date</th>
        <th>Link</th>
        <th></th>
    </tr>
</thead>

            <tbody>
                <?php
$publicKey = "GCYIUGL3JVXJ6JULDKUCYKDFUF56UWE7AHT2GM2MM5ECYWADCNMLJ4KG"; // your source account

$apiUrl = "https://horizon-testnet.stellar.org/accounts/$publicKey/payments";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$payments = $data['_embedded']['records'];

$SrNo = 0;

foreach ($payments as $payment) {
    if ($payment['type'] === 'payment') {
        $SrNo++;
        $amount = $payment['amount'];
        $asset = ($payment['asset_type'] === 'native') ? 'XLM' : $payment['asset_code'];
        $to = $payment['to'];
        $from = $payment['from'];
        $hash = $payment['transaction_hash'];
        $created = $payment['created_at'];
?>
        <tr>
            <td class="align-middle text-sm"><?php echo $SrNo; ?></td>
            <td class="align-middle text-sm"><?php echo $from; ?> ➡️ <?php echo $to; ?></td>
            <td class="align-middle text-sm"><?php echo $amount; ?></td>
            <td class="align-middle text-sm"><?php echo $asset; ?></td>
            <td class="align-middle text-sm"><?php echo $created; ?></td>
            <td class="align-middle text-sm"><a href="https://testnet.stellar.expert/explorer/testnet/tx/<?php echo $hash; ?>" target="_blank">View</a></td>
            <td class="align-middle text-sm"></td>
        </tr>
<?php
    }
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
