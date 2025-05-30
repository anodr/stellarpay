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
<style>
  .wide-textarea {
    width: 100%;
    min-width: 600px;
    height: 8em !important;
    resize: vertical;
  }
</style>
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
		    

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="../index.php">Home</a> /</span> Contact Support</h4>

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
				
				
<div class="row">
  <!-- Inquiry Form -->
  <div class="col-md-6 col-lg-6"> 
    <form action="add_inquiries.php" method="POST" class="forms-sample" enctype="multipart/form-data" autocomplete="off">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h4 class="card-title mb-3">Send Us Your Inquiry</h4>
          <p class="text-muted small mb-4">Have a question or concern? Send us your inquiry and we’ll respond as soon as possible.</p>
          
          <div class="form-floating form-floating-outline mb-3">
            <textarea class="form-control" id="exampleFormControlInput1" name="inquiry" placeholder="Enter your inquiry here..." rows="6" required></textarea>
            <label for="exampleFormControlInput1">Your Inquiry</label>
          </div>

          <div class="form-floating form-floating-outline">
            <button type="submit" class="btn btn-primary w-100">Send Inquiry</button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <!-- Contact Information -->
  <div class="col-md-6 col-lg-6"> 
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <h4 class="card-title mb-3">Contact Information</h4>
        <p class="mb-2"><strong>Location:</strong> Dar es Salaam</p>
        <p class="mb-2"><strong>Plot No:</strong> [Enter Plot Number]</p>
        <p class="mb-2"><strong>House No:</strong> [Enter House Number]</p>
        <p class="mb-2"><strong>Phone:</strong> <a href="tel:+255759876418">+255 759 876 418</a></p>
        <p class="mb-2"><strong>WhatsApp:</strong> <a href="https://wa.me/255759876418" target="_blank">+255 759 876 418</a></p>
        <p class="text-muted small mt-3">We're here to help you 7 days a week. Expect a response within hours.</p>
      </div>
    </div>
  </div>
</div>

				
				
				
				
				<div><hr></div>
				

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <h5 class="card-header">Inquiries History</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-items-center" id="dataTable">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Inquiry</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                require_once('../includes/DB.php');
                                $SrNo = 0;

                                if (isset($_SESSION['login'])) {
                                    $ID = $_SESSION['username'];

                                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "SELECT * FROM inquiries WHERE inquiryby = :user ORDER BY dateinquiry DESC";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bindParam(':user', $ID);
                                    $stmt->execute();

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $SrNo++;
                                        $inquiry = htmlspecialchars($row['inquiry']);
                                        $DateTime = date("d M Y H:i", strtotime($row['dateinquiry']));
                                        $Id = $row['inquiries_id'];
                            ?>
                                        <tr>
                                            <td><?php echo $SrNo; ?></td>
                                            <td><?php echo nl2br($inquiry); ?></td>
                                            <td><?php echo $DateTime; ?></td>
                                            <td>
                                                <a href="delete_inquiry.php?id=<?php echo urlencode($Id); ?>" class="text-danger" onclick="return confirm('Are you sure you want to delete this inquiry?');">
                                                    <i class="mdi mdi-delete-forever"></i>
                                                </a>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="4">You must be logged in to view your inquiries.</td></tr>';
                                }
                            } catch (Exception $e) {
                                echo '<tr><td colspan="4">Error: ' . $e->getMessage() . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
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
