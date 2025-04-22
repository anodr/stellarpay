<?php ob_start(); ?>
<?php session_start(); ?>
<?php require_once("../loginpanel/includes/connect.php"); ?>
<?php //require_once("../include/sessions.php"); ?> 
<?php require_once("../loginpanel/includes/functions.php"); ?>
<?php Confirm_Login(); ?>


<?php
// Check if the form is submitted
if(isset($_POST['Submit'])) {
    try {
        require_once('../includes/DB.php');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $exc) {
        echo $exc->getMessage(); // Output detailed error message
        exit(); // Exit script in case of database connection error
    }

    // Get values from input fields
    $matumizi = htmlspecialchars($_POST['matumizi']); // Sanitize input
	$kiasiJuu = htmlspecialchars($_POST['kiasiJuu']); // Sanitize input

    // Validate and sanitize the ID from URL
    $PostIDFromURL = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($PostIDFromURL === false) {
        // Invalid ID, handle error or redirect user
        $_SESSION["ErrorMessage"] = "Invalid Announcement ID";
        header("Location: error.php");
        exit();
    }

    // Update data in the database
    $query = "UPDATE `matumizi` SET `matumizi` = :matumizi, `kiasiJuu` = :kiasiJuu WHERE `matumizi_id` = :id";
    $pdoResult = $conn->prepare($query);
    $pdoExec = $pdoResult->execute(array(":matumizi" => $matumizi, ":kiasiJuu" => $kiasiJuu, ":id" => $PostIDFromURL));

    // Check if the update was successful
    if ($pdoExec) {
        $_SESSION["SuccessMessage"] = "matumizi Updated Successfully";
        header('Location: matumizi.php'); // Redirect to announcements page
        exit();
    } else {
        $_SESSION["ErrorMessage"] = "Error: Announcement Not Updated";
        header("Location: error.php"); // Redirect to error page
        exit();
    }
}
?>


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

    <title>C_POS - Point of sale and accounting system</title>

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

          <!-- / Navbar -->
		  
		  
<?php
// Include your database connection 
require_once('../includes/DB.php');
$PostIDFromURL = $_GET["id"]; 
?>

<?php
    require_once('../includes/DB.php');
   
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	if(isset($_SESSION['login'])){
		           $ID = $_SESSION['id'];
				  }
   
	$res1 = $conn->prepare("SELECT matumizi as matumizi, kiasiJuu as kiasiJuu
	                        FROM matumizi WHERE matumizi_id = '$PostIDFromURL' AND matumiziby='$ID' ");
    $res1->execute();
	$row = $res1->fetch(PDO::FETCH_ASSOC);
	
	$matumizi = ($row['matumizi']);
	$kiasiJuu = ($row['kiasiJuu']);
?>		  

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="py-3 mb-4">
               <div class="text-muted fw-light">
                 <span class="text-small"><a href="../index.php" class="text-small">Home</a> / <a href="matumizi.php" class="text-small">Back</a> </span>
               </div>
              </div>


<form action="updatematumizi.php?id=<?php echo $PostIDFromURL; ?>" method="POST" class="forms-sample" enctype="multipart/form-data" autocomplete="off">
    <div class="col-12">
        <div class="card">
            <hr class="m-0" />
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12">
                        <small class="text-light fw-medium"></small>
                        <div class="mt-3">
                            <div class="mb-3">
                                <label for="heading" class="form-label">matumizi</label>
                                <input type="text" class="form-control" id="heading" name="matumizi" value="<?php echo $matumizi; ?>">
                            </div>
							<div class="mb-3">
                                <label for="kiasiJuu" class="form-label">Kiasi Juu</label>
                                <input type="text" class="form-control" id="kiasiJuu" name="kiasiJuu" value="<?php echo $kiasiJuu; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-1 mt-md-3">
        <div class="d-grid mt-3 mt-md-4">
            <button class="btn btn-primary" type="submit" name="Submit">Hariri</button>
        </div>
    </div>
</form>



				
				<div><br></div>

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
    $('#dataTable').DataTable();
});
</script>



	
  </body>
</html>
