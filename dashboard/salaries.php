<?php
require_once('includes/DB.php');

if(isset($_SESSION['login'])){
    $ID = $_SESSION['username'];
}

// Sales Today
$Today = strtotime("Today Midnight");
$StartToday = date("Y-m-d 00:00:00", $Today);
$FinishToday = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(salaryAmount) as salariesToday FROM salaries
                        WHERE datesalary BETWEEN '$StartToday' AND '$FinishToday'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$salariesToday = ($row['salariesToday']);

// Sales Week
$Sunday = strtotime("This week");
$StartWeek = date("Y-m-d 00:00:00", $Sunday);
$FinishWeek = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(salaryAmount) as salariesWeek FROM salaries
                        WHERE datesalary BETWEEN '$StartWeek' AND '$FinishWeek'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$salariesWeek = ($row['salariesWeek']);

// Sales Month
$ThisMonth = strtotime("First day of this month");
$StartMonth = date("Y-m-d 00:00:00", $ThisMonth);
$FinishMonth = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(salaryAmount) as salariesMonth FROM salaries
                        WHERE datesalary BETWEEN '$StartMonth' AND '$FinishMonth'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$salariesMonth = ($row['salariesMonth']);

// Calculate start of current quarter
$QuarterStart = strtotime(date('Y-m-01 00:00:00', strtotime('-'.(date('n') - 1) % 3 .' month')));

// Format the start of quarter as YYYY-MM-DD 00:00:00
$StartQuarter = date("Y-m-d 00:00:00", $QuarterStart);

// Calculate end of current quarter
$EndQuarter = strtotime(date('Y-m-d 23:59:59', strtotime('+2 months', $QuarterStart)));

// Format the end of quarter as YYYY-MM-DD 23:59:59
$EndQuarterFormatted = date("Y-m-d H:i:s", $EndQuarter);

// Execute query to fetch sales data for the current quarter
$res = $conn->prepare("SELECT SUM(salaryAmount) as salariesQuarter FROM salaries
                        WHERE datesalary >= '$StartQuarter' AND datesalary <= '$EndQuarterFormatted'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$salariesQuarter = ($row['salariesQuarter']);

// Output the result (you can format this as needed for your display)
//echo "Sales for the current quarter: " . ($salariesQuarter); // Example output formatting


// Sales Year
$StartYear = date('Y-01-01 00:00:00');
$FinishYear = date("Y-m-d H:i:s");

$res = $conn->prepare("SELECT SUM(salaryAmount) as salariesYear FROM salaries
                        WHERE datesalary BETWEEN '$StartYear' AND '$FinishYear'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$salariesYear = ($row['salariesYear']);

// Sales Total
$res = $conn->prepare("SELECT SUM(salaryAmount) as salariesTotal FROM salaries WHERE salaryby = '$ID'");
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
$salariesTotal = ($row['salariesTotal']);
?>
