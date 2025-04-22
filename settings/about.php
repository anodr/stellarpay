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

    <title>Investmentpro - Investmentpro Management System</title>

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
		    

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 mb-4"><span class="text-muted fw-light"><a href="../index.php">Home</a> /</span> Weka taarifa za biashara yako</h4>

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
                }, 3000);
            </script>";
    }
    ?>
</div>
			  
			 <form action="add_company.php" method="POST" class="forms-sample"  enctype="multipart/form-data" autocomplete="off">
                <div class="">
    <div class="card mb-4">
        <div class="card-body">
                <div class="row gx-3 gy-2 align-items-center gap-3 gap-md-0">
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline">
                            <input
                          type="text"
                          class="form-control"
                          id="defaultFormControlInput"
						  name="companyName"
                          placeholder="Jina la biashara"
                          aria-describedby="defaultFormControlHelp" REQUIRED />
                            <label for="start_date">Jina la biashara*</label>
                        </div>
                    </div>
                </div>
        </div>
    </div>
              
			  <div class="row">
	 
			  <div class="col-md-12">
                  <div class="card mb-4">
                    <div class="card-body demo-vertical-spacing demo-only-element">
                      <div class="form-floating form-floating-outline mb-4">
                        <input
                          type="email"
                          class="form-control"
                          id="exampleFormControlInput1"
						  name="companyEmail"
                          placeholder="name@company.com" />
                        <label for="exampleFormControlInput1">Email</label>
                      </div>
					  <div class="form-floating form-floating-outline mb-4">
                        <input
                          type="text"
                          class="form-control"
                          id="exampleFormControlInput1"
						  name="companyPhone"
                          placeholder="+255123456789" REQUIRED />
                        <label for="exampleFormControlInput1">Namba ya simu*</label>
                      </div>
					  <div class="form-floating form-floating-outline mb-4">
                        <input
                          type="text"
                          class="form-control"
                          id="exampleFormControlInput1"
						  name="TIN"
                          placeholder="Namba ya TIN" REQUIRED />
                        <label for="exampleFormControlInput1">TIN Number *</label>
                      </div>
					  <div class="form-floating form-floating-outline mb-4">
                        <input
                          type="text"
                          class="form-control"
                          id="exampleFormControlInput1"
						  name="VRN"
                          placeholder="Value Added Tax (VAT) Registration Number" />
                        <label for="exampleFormControlInput1">VRN (Optional)</label>
                      </div>
                      <div class="form-floating form-floating-outline mb-4">
                        <textarea
                          class="form-control h-px-100"
                          id="exampleFormControlTextarea1"
						  name="companyAddress"
                          placeholder="Anwani..." REQUIRED></textarea>
                        <label for="exampleFormControlTextarea1">Anwani*</label>
                      </div>
					  <div class="form-floating form-floating-outline mb-4" HIDDEN>
<select class="form-select" name="branchUser" id="" aria-label="Default select example" >
    <option value="" selected>Chagua tawi ...</option> <!-- Default option -->
    <?php
    try {
        require_once('../includes/DB.php');

        if (isset($_SESSION['login'])) {
            $ID = $_SESSION['id'];
        }

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // If no custom date filter is applied, retrieve all data
        $sql = "SELECT * FROM branch ORDER BY branch ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $branch = $row['branch'];
            $Id = $row['clients_id'];
            ?>
            <option value="<?php echo $branch; ?>"><?php echo $branch; ?></option>
            <?php
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        echo "Errors: " . $error;
    }
    ?>
</select>
                        <label for="exampleFormControlInput1">Tawi / Branch</label>
                      </div>
					  <div class="mb-3">
                        <label for="formFile" class="form-label">Logo</label>
                        <input class="form-control" type="file" name="File" id="formFile" />
                      </div>
					  
					  <div class="form-floating form-floating-outline mb-4">
				 <button type="submit" class="btn btn-primary form-control">Save</button>
				</div>
				
				</div>
				
				
				
				</div>
				</div>
				
				</form>
				</div>
				
				<div><hr></div>
				
<div class="row">
    <!-- Hoverable Table rows -->
    
    <?php
    require_once('../includes/DB.php');

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_SESSION['login'])) {
        $ID = $_SESSION['username'];
    }

    $res1 = $conn->prepare("SELECT company_id AS company_id, companyName AS companyName, 
	                       companyEmail AS companyEmail, companyPhone AS companyPhone, 
						   TIN AS TIN, VRN AS VRN 
						   FROM company WHERE companyby='$ID'");
    $res1->execute();
    $row = $res1->fetch(PDO::FETCH_ASSOC);
    
    $companyName  = $row['companyName'] ?? '';
    $companyEmail = $row['companyEmail'] ?? '';
    $companyPhone = $row['companyPhone'] ?? '';
	$TIN          = $row['TIN'] ?? '';
	$VRN          = $row['VRN'] ?? '';
    $company_id   = $row['company_id'] ?? '';
    ?>                  

    <div class="card">
        <h5 class="card-header">Taarifa za biashara &nbsp; <a href="updatecompany.php?id=<?php echo $company_id; ?>"><i class="mdi mdi-pencil"></i>Hariri</a></h5>
        <div class="card-body">  
            <div class="demo-inline-spacing mt-3">
                <ul class="list-group">
                    <?php
                    try {
                        require_once('../includes/DB.php');

                        if (isset($_SESSION['login'])) {
                            $ID = $_SESSION['username'];
                        }

                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Retrieve all data from the loanapplications table
                        $sql = "SELECT * FROM company WHERE companyby='$ID'";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $companyName = $row['companyName'] ?? '';
                            $companyEmail = $row['companyEmail'] ?? '';
                            $companyPhone = $row['companyPhone'] ?? '';
                            $companyAddress = $row['companyAddress'] ?? '';
							$branchUser = $row['branchUser'] ?? '';
                            $companyLogo = $row['file'] ?? '';
                    ?>
                            <li class="list-group-item">
                                <strong>Jina la biashara:</strong> <?php echo $companyName; ?><hr>
                                <strong>Email:</strong> <?php echo $companyEmail; ?><hr>
                                <strong>Namba ya simu:</strong> <?php echo $companyPhone; ?><hr>
								<strong>Taxpayer Identification Number:</strong> <?php echo $TIN; ?><hr>
								<strong>Value Added Tax (VAT) Registration Number:</strong> <?php echo $VRN; ?><hr>
                                <strong>Branch:</strong> <?php echo $branchUser; ?><hr>
								<strong>Anwani:</strong> <?php echo $companyAddress; ?><hr>
                                <strong>Logo:</strong> <img class="card-img card-img-left" style="width:30%;" src="uploads/<?php echo $companyLogo; ?>" alt="<?php echo $companyLogo; ?>" /><hr>
                            </li>
                    <?php
                        }
                    } catch (Exception $e) {
                        $error = $e->getMessage();
                        echo "Errors: " . $error;
                    }
                    ?>
                </ul>
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
