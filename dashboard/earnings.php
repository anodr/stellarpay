<?php
require_once('includes/DB.php');

if (!isset($_SESSION['login'])) {
    echo "<div class='alert alert-danger'>Please login to view commissions.</div>";
    exit;
}

$username = $_SESSION['username'];
date_default_timezone_set('Africa/Dar_es_Salaam');

// Enhanced commission function with error handling
function getCommission($conn, $user, $startDate, $endDate) {
    try {
        $stmt = $conn->prepare("SELECT SUM(agent_fee_xlm) AS total_commission FROM transactions 
                                WHERE user = :user AND created_at BETWEEN :start AND :end");
        $stmt->execute([
            ':user' => $user,
            ':start' => $startDate,
            ':end' => $endDate
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_commission'] ? floatval($row['total_commission']) : 0.0;
    } catch (PDOException $e) {
        error_log("Commission Error: " . $e->getMessage());
        return 0.0;
    }
}

// Get exchange rates with fallback
$exchangeRate = [
    'xlmToUsd' => null,
    'usdToTsh' => 2500,
    'xlmToTsh' => null
];

$response = @file_get_contents('https://api.coingecko.com/api/v3/simple/price?ids=stellar&vs_currencies=usd');
if ($response) {
    $data = json_decode($response, true);
    if (isset($data['stellar']['usd'])) {
        $exchangeRate['xlmToUsd'] = $data['stellar']['usd'];
        $exchangeRate['xlmToTsh'] = $exchangeRate['xlmToUsd'] * $exchangeRate['usdToTsh'];
    }
}

// Date ranges
$now = new DateTime();
$ranges = [
    'today' => [
        'start' => (clone $now)->setTime(0, 0),
        'end' => $now
    ],
    'week' => [
        'start' => (clone $now)->modify('this week midnight'),
        'end' => $now
    ],
    'month' => [
        'start' => (clone $now)->modify('first day of this month midnight'),
        'end' => $now
    ],
    'year' => [
        'start' => (clone $now)->modify('first day of january this year midnight'),
        'end' => $now
    ],
    'all' => [
        'start' => new DateTime('2000-01-01'),
        'end' => $now
    ]
];

// Get commissions for all ranges
$commissions = [];
foreach ($ranges as $key => $range) {
    $commissions[$key] = [
        'xlm' => getCommission($conn, $username, $range['start']->format('Y-m-d H:i:s'), $range['end']->format('Y-m-d H:i:s')),
        'tsh' => $exchangeRate['xlmToTsh'] ? getCommission($conn, $username, $range['start']->format('Y-m-d H:i:s'), $range['end']->format('Y-m-d H:i:s')) * $exchangeRate['xlmToTsh'] : null
    ];
}
?>

