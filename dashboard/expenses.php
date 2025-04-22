<?php
require_once('includes/DB.php');

date_default_timezone_set('Africa/Dar_es_Salaam');

if (isset($_SESSION['login'])) {
    $ID = $_SESSION['username'];
}

try {
    // Get the current date and time
    $currentDateTime = date('Y-m-d');

    // Get the start of the current month
    $startOfMonth = date('Y-m-01');

    // Get the start of the current year
    $startOfYear = date('Y-01-01');

    // Determine the start of the current quarter
    $currentMonth = date('m');
    $currentQuarter = ceil($currentMonth / 3);
    $startOfQuarter = date('Y-m-d', strtotime(date('Y') . '-' . (($currentQuarter - 1) * 3 + 1) . '-01'));

    // Set $from to the start of the current month
    $fromMonth = $startOfMonth;

    // Set $to to the current date and time
    $to = $currentDateTime;

    // Set $from to the start of the current year
    $fromYear = $startOfYear;

    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query for Today
    $stmtToday = $conn->prepare("SELECT SUM(garamaMatumizi) as garamaMatumizi 
                                FROM expenses 
                                WHERE DATE(dateexpense) = :currentDateTime ");
    $stmtToday->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);
    $stmtToday->execute();
    $rowToday = $stmtToday->fetch(PDO::FETCH_ASSOC);
    $garamaMatumiziToday = ($rowToday) ? $rowToday['garamaMatumizi'] : 0;

    // Query for This Week
    $stmtWeek = $conn->prepare("SELECT SUM(garamaMatumizi) as garamaMatumizi 
                                FROM expenses 
                                WHERE YEARWEEK(dateexpense) = YEARWEEK(:currentDateTime)");
    $stmtWeek->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);
    $stmtWeek->execute();
    $rowWeek = $stmtWeek->fetch(PDO::FETCH_ASSOC);
    $garamaMatumiziWeek = ($rowWeek) ? $rowWeek['garamaMatumizi'] : 0;

    // Query for This Month
    $stmtMonth = $conn->prepare("SELECT SUM(garamaMatumizi) as garamaMatumizi 
                                FROM expenses 
                                WHERE DATE(dateexpense) BETWEEN :fromMonth AND :to");
    $stmtMonth->bindParam(':fromMonth', $fromMonth, PDO::PARAM_STR);
    $stmtMonth->bindParam(':to', $to, PDO::PARAM_STR);
    $stmtMonth->execute();
    $rowMonth = $stmtMonth->fetch(PDO::FETCH_ASSOC);
    $garamaMatumiziMonth = ($rowMonth) ? $rowMonth['garamaMatumizi'] : 0;

    // Query for This Quarter
    $stmtQuarter = $conn->prepare("SELECT SUM(garamaMatumizi) as garamaMatumizi 
                                   FROM expenses 
                                   WHERE DATE(dateexpense) BETWEEN :startOfQuarter AND :to");
    $stmtQuarter->bindParam(':startOfQuarter', $startOfQuarter, PDO::PARAM_STR);
    $stmtQuarter->bindParam(':to', $to, PDO::PARAM_STR);
    $stmtQuarter->execute();
    $rowQuarter = $stmtQuarter->fetch(PDO::FETCH_ASSOC);
    $garamaMatumiziQuarter = ($rowQuarter) ? $rowQuarter['garamaMatumizi'] : 0;

    // Query for This Year
    $stmtYear = $conn->prepare("SELECT SUM(garamaMatumizi) as garamaMatumizi 
                                FROM expenses 
                                WHERE DATE(dateexpense) BETWEEN :fromYear AND :to");
    $stmtYear->bindParam(':fromYear', $fromYear, PDO::PARAM_STR);
    $stmtYear->bindParam(':to', $to, PDO::PARAM_STR);
    $stmtYear->execute();
    $rowYear = $stmtYear->fetch(PDO::FETCH_ASSOC);
    $garamaMatumiziYear = ($rowYear) ? $rowYear['garamaMatumizi'] : 0;

    // Query for All Time
    $stmtAllTime = $conn->prepare("SELECT SUM(garamaMatumizi) as garamaMatumizi FROM expenses");
    $stmtAllTime->execute();
    $rowAllTime = $stmtAllTime->fetch(PDO::FETCH_ASSOC);
    $garamaMatumiziTotal = ($rowAllTime) ? $rowAllTime['garamaMatumizi'] : 0;

} catch (PDOException $e) {
    // Handle any errors
    echo 'ERROR: ' . $e->getMessage();
}
?>
