<?php 
ob_start(); 
//session_start();  // Ensure the session is started

// Login check function
function Login(){
    if(isset($_SESSION["id"])){
        return true;  // User is logged in
    }
    return false;  // User is not logged in
}

// Confirm login function for admin pages
function Confirm_Login(){
    if(!Login()){
        $_SESSION["ErrorMessage"]="Login Required";
        header("Location: auth_login.php"); // Redirect to login if not logged in
        exit;  // Always exit after header redirection to stop further code execution
    }
}

// Confirm login for front-end pages
function ConfirmFrontPage_Login(){
    if(!Login()){
        $_SESSION["ErrorMessage"]="Login Required";
        header("Location: ../auth_login.php"); // Redirect to login if not logged in
        exit;  // Always exit after header redirection
    }
}

// Display success message
function SuccessMessage(){
    if(isset($_SESSION["SuccessMessage"])){
        $Output="<div class=\"alert alert-success\">";
        $Output .= htmlentities($_SESSION["SuccessMessage"]);  // Escape special characters
        $Output .= "</div>";
        $_SESSION["SuccessMessage"]=null;  // Reset the session message after displaying
        return $Output;
    }
}

// Display error message
function ErrorMessage(){
    if(isset($_SESSION["ErrorMessage"])){
        $Output="<div class=\"alert alert-warning\">";
        $Output .= htmlentities($_SESSION["ErrorMessage"]);  // Escape special characters
        $Output .= "</div>";
        $_SESSION["ErrorMessage"]=null;  // Reset the session message after displaying
        return $Output;
    }
}
?>
