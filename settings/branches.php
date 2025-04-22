<?php ob_start(); ?>
<?php session_start(); ?>
<?php require_once("../loginpanel/includes/connect.php"); ?>
<?php //require_once("../include/sessions.php"); ?> 
<?php require_once("../loginpanel/includes/functions.php"); ?>
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

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="../index.php">Home</a> / Settings /</span> Matawi</h4>

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
			  
			 <form action="add_branch.php" method="POST" class="forms-sample"  enctype="multipart/form-data" autocomplete="off">

                <!-- Default -->
                <div class="col-md-12">
                  <div class="card mb-4">
                    <p class="card-header">Jina la Tawi</p>
                    <div class="card-body">
					  <div class="form-floating form-floating-outline mb-2">
                        <input
                          type="text"
                          class="form-control" 
						  name="branch"
                          id="defaultFormControlInput"
                          placeholder="Jina la Tawi"
                          aria-describedby="defaultFormControlHelp" REQUIRED />
						  <label for="exampleFormControlInput1">Jina la Tawi</label>
                      </div>
                    </div>
                  </div>
                </div>
				
				<div class="form-floating form-floating-outline mb-2">
				 <button type="submit" class="btn btn-primary form-control">Save</button>
				</div>
				
				</form>
				</div>
				
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
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tawi</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tarehe</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    require_once('../includes/DB.php');

                    if (isset($_SESSION['login'])) {
                        $ID = $_SESSION['id'];
                    }

                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // If no custom date filter is applied, retrieve all data
                        $sql = "SELECT * FROM branch WHERE branchby = '$ID' ORDER BY datebranch DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
						
                    $SrNo = 0;

                    while ($row = $stmt->fetch()) {
                        $SrNo++;
                        $branch = $row['branch'];
                        $DateTime = $row['datebranch'];
                        $Id = $row['branch_id'];
                ?>
                        <tr>
                            <td class="align-middle text-sm"><?php echo $SrNo; ?></td>
                            <td class="align-middle text-sm"><?php echo ($branch); ?></td>
                            <td class="align-middle text-sm"><?php echo ($DateTime); ?></td>
                            <td class="align-middle text-sm"><a href="updatebranch.php?id=<?php echo $Id; ?>"><i class="mdi mdi-pencil"></i></a></td>
                            <td class="align-middle text-sm"><a href="delete_branch.php?id=<?php echo $Id; ?>"><i class="mdi mdi-delete-forever"></i></a></td>
                        </tr>
                <?php }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    echo "Errors: " . $error;
                } ?>
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
    $('#dataTable').DataTable();
});
</script>



	
  </body>
</html>
