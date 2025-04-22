<?php ob_start(); 
session_start();
?>
<?php

try {
  require_once('../includes/DB.php');
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  if(isset($_SESSION['login'])){
		  $Admin = $_SESSION['id'];
		}
  
  $sql = "DELETE FROM branch WHERE branch_id=?";
  $result = $conn->prepare($sql);
  $res = $result->execute(array($_GET['id']));
  if ($res) { 
	//header("location:javascript://history.go(-1)");
	$_SESSION["SuccessMessage"]="Tawi limefutwa Kikamilifu";
	header('Location: ' . $_SERVER['HTTP_REFERER']);
 }else{
	echo "Failed to Delete Record";
 }
  
 } catch (Exception $e) {
  $error = $e->getMessage();
}
