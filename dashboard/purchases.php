<?php
require_once('includes/DB.php');

if(isset($_SESSION['login'])){
    $ID = $_SESSION['username'];
}

date_default_timezone_set('Africa/Dar_es_Salaam');

// Sales Today
$Today = strtotime("Today Midnight");
$StartToday = date("Y-m-d 00:00:00", $Today);
$FinishToday = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(beiPurchased * idadiPurchased) as purchaseToday FROM manunuzi
                        WHERE datemanunuzi BETWEEN '$StartToday' AND '$FinishToday'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$purchaseToday = ($row['purchaseToday']);

// Sales Week
$Sunday = strtotime("This week");
$StartWeek = date("Y-m-d 00:00:00", $Sunday);
$FinishWeek = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(beiPurchased * idadiPurchased) as purchaseWeek FROM manunuzi
                        WHERE datemanunuzi BETWEEN '$StartWeek' AND '$FinishWeek'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$purchaseWeek = ($row['purchaseWeek']);

// Sales Month
$ThisMonth = strtotime("First day of this month");
$StartMonth = date("Y-m-d 00:00:00", $ThisMonth);
$FinishMonth = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(beiPurchased * idadiPurchased) as purchaseMonth FROM manunuzi
                        WHERE datemanunuzi BETWEEN '$StartMonth' AND '$FinishMonth'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$purchaseMonth = ($row['purchaseMonth']);

// Calculate start of current quarter
$QuarterStart = strtotime(date('Y-m-01 00:00:00', strtotime('-'.(date('n') - 1) % 3 .' month')));

// Format the start of quarter as YYYY-MM-DD 00:00:00
$StartQuarter = date("Y-m-d 00:00:00", $QuarterStart);

// Calculate end of current quarter
$EndQuarter = strtotime(date('Y-m-d 23:59:59', strtotime('+2 months', $QuarterStart)));

// Format the end of quarter as YYYY-MM-DD 23:59:59
$EndQuarterFormatted = date("Y-m-d H:i:s", $EndQuarter);

// Execute query to fetch sales data for the current quarter
$res = $conn->prepare("SELECT SUM(beiPurchased * idadiPurchased) as purchaseQuarter FROM manunuzi
                        WHERE datemanunuzi >= '$StartQuarter' AND datemanunuzi <= '$EndQuarterFormatted'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$purchaseQuarter = ($row['purchaseQuarter']);

// Output the result (you can format this as needed for your display)
//echo "Sales for the current quarter: " . number_format($manunuziQuarter, 2); // Example output formatting

// Sales Year
$StartYear = date('Y-01-01 00:00:00');
$FinishYear = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(beiPurchased * idadiPurchased) as purchaseYear FROM manunuzi
                        WHERE datemanunuzi BETWEEN '$StartYear' AND '$FinishYear'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$purchaseYear = ($row['purchaseYear']);

// Sales Total
$res = $conn->prepare("SELECT SUM(beiPurchased * idadiPurchased) as purchaseTotal FROM manunuzi");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$purchaseTotal = ($row['purchaseTotal']);
?>