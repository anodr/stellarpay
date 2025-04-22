<?php
require_once('includes/DB.php');

if(isset($_SESSION['login'])){
    $ID = $_SESSION['username'];
}

// Sales Today
$Today = strtotime("Today Midnight");
$StartToday = date("Y-m-d 00:00:00", $Today);
$FinishToday = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(wagesAmount) as wagesToday FROM wages
                        WHERE datewages BETWEEN '$StartToday' AND '$FinishToday'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$wagesToday = ($row['wagesToday']);

// Sales Week
$Sunday = strtotime("This week");
$StartWeek = date("Y-m-d 00:00:00", $Sunday);
$FinishWeek = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(wagesAmount) as wagesWeek FROM wages
                        WHERE datewages BETWEEN '$StartWeek' AND '$FinishWeek'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$wagesWeek = ($row['wagesWeek']);

// Sales Month
$ThisMonth = strtotime("First day of this month");
$StartMonth = date("Y-m-d 00:00:00", $ThisMonth);
$FinishMonth = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(wagesAmount) as wagesMonth FROM wages
                        WHERE datewages BETWEEN '$StartMonth' AND '$FinishMonth'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$wagesMonth = ($row['wagesMonth']);

// Calculate start of current quarter
$QuarterStart = strtotime(date('Y-m-01 00:00:00', strtotime('-'.(date('n') - 1) % 3 .' month')));

// Format the start of quarter as YYYY-MM-DD 00:00:00
$StartQuarter = date("Y-m-d 00:00:00", $QuarterStart);

// Calculate end of current quarter
$EndQuarter = strtotime(date('Y-m-d 23:59:59', strtotime('+2 months', $QuarterStart)));

// Format the end of quarter as YYYY-MM-DD 23:59:59
$EndQuarterFormatted = date("Y-m-d H:i:s", $EndQuarter);

// Execute query to fetch sales data for the current quarter
$res = $conn->prepare("SELECT SUM(wagesAmount) as wagesQuarter FROM wages
                        WHERE datewages >= '$StartQuarter' AND datewages <= '$EndQuarterFormatted'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$wagesQuarter = ($row['wagesQuarter']);

// Output the result (you can format this as needed for your display)
//echo "Sales for the current quarter: " . ($wagesQuarter); // Example output formatting


// Sales Year
$StartYear = date('Y-01-01 00:00:00');
$FinishYear = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(wagesAmount) as wagesYear FROM wages
                        WHERE datewages BETWEEN '$StartYear' AND '$FinishYear'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$wagesYear = ($row['wagesYear']);

// Sales Total
$res = $conn->prepare("SELECT SUM(wagesAmount) as wagesTotal FROM wages WHERE wagesby = '$ID'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$wagesTotal = ($row['wagesTotal']);
?>
