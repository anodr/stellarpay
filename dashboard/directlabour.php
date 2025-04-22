<?php
require_once('includes/DB.php');

if (isset($_SESSION['login'])) {
    $ID = $_SESSION['id'];
}

// Function to execute a prepared statement and fetch a single result
function fetchSingleResult($conn, $query, $params) {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Labour Today
$Today = strtotime("Today Midnight");
$StartToday = date("Y-m-d 00:00:00", $Today);
$FinishToday = date("Y-m-d H:i:s");

$queryToday = "SELECT SUM(labourAmount) as productionlabourToday FROM productionlabour
               WHERE dateProductionLabour BETWEEN :startToday AND :finishToday";
$paramsToday = [
    ':startToday' => $StartToday,
    ':finishToday' => $FinishToday
];
$rowToday = fetchSingleResult($conn, $queryToday, $paramsToday);
$productionlabourToday = ($rowToday['productionlabourToday'] ?? 0);

// Labour Week
$Sunday = strtotime("This week");
$StartWeek = date("Y-m-d 00:00:00", $Sunday);
$FinishWeek = date("Y-m-d H:i:s");

$queryWeek = "SELECT SUM(labourAmount) as productionlabourWeek FROM productionlabour
              WHERE dateProductionLabour BETWEEN :startWeek AND :finishWeek";
$paramsWeek = [
    ':startWeek' => $StartWeek,
    ':finishWeek' => $FinishWeek
];
$rowWeek = fetchSingleResult($conn, $queryWeek, $paramsWeek);
$productionlabourWeek = ($rowWeek['productionlabourWeek'] ?? 0);

// Labour Month
$ThisMonth = strtotime("First day of this month");
$StartMonth = date("Y-m-d 00:00:00", $ThisMonth);
$FinishMonth = date("Y-m-d H:i:s");

$queryMonth = "SELECT SUM(labourAmount) as productionlabourMonth FROM productionlabour
               WHERE dateProductionLabour BETWEEN :startMonth AND :finishMonth";
$paramsMonth = [
    ':startMonth' => $StartMonth,
    ':finishMonth' => $FinishMonth
];
$rowMonth = fetchSingleResult($conn, $queryMonth, $paramsMonth);
$productionlabourMonth = ($rowMonth['productionlabourMonth'] ?? 0);

// Calculate start of current quarter
$QuarterStart = strtotime(date('Y-m-01 00:00:00', strtotime('-'.(date('n') - 1) % 3 .' month')));
$StartQuarter = date("Y-m-d 00:00:00", $QuarterStart);

// Calculate end of current quarter
$EndQuarter = strtotime(date('Y-m-d 23:59:59', strtotime('+2 months', $QuarterStart)));
$EndQuarterFormatted = date("Y-m-d H:i:s", $EndQuarter);

$queryQuarter = "SELECT SUM(labourAmount) as productionlabourQuarter FROM productionlabour
                 WHERE dateProductionLabour BETWEEN :startQuarter AND :endQuarter";
$paramsQuarter = [
    ':startQuarter' => $StartQuarter,
    ':endQuarter' => $EndQuarterFormatted
];
$rowQuarter = fetchSingleResult($conn, $queryQuarter, $paramsQuarter);
$productionlabourQuarter = ($rowQuarter['productionlabourQuarter'] ?? 0);

// Labour Year
$StartYear = date('Y-01-01 00:00:00');
$FinishYear = date("Y-m-d H:i:s");

$queryYear = "SELECT SUM(labourAmount) as productionlabourYear FROM productionlabour
              WHERE dateProductionLabour BETWEEN :startYear AND :finishYear";
$paramsYear = [
    ':startYear' => $StartYear,
    ':finishYear' => $FinishYear
];
$rowYear = fetchSingleResult($conn, $queryYear, $paramsYear);
$productionlabourYear = ($rowYear['productionlabourYear'] ?? 0);

// Labour Total
$queryTotal = "SELECT SUM(labourAmount) as productionlabourTotal FROM productionlabour WHERE productionLabourBy = :ID";
$paramsTotal = [
    ':ID' => $ID
];
$rowTotal = fetchSingleResult($conn, $queryTotal, $paramsTotal);
$productionlabourTotal = ($rowTotal['productionlabourTotal'] ?? 0);
?>
