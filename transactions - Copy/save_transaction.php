<?php
ob_start();
session_start();
include('../includes/DB.php');

if (isset($_SESSION['login'])) {
    $Admin = $_SESSION['username'];
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['tx_hash'], $data['amount'], $data['destination'], $data['source'], $data['status'])) {
    echo json_encode(["success" => false, "message" => "Missing required parameters"]);
    exit();
}

$tx_hash = $data['tx_hash'];
$amount = $data['amount'];
$destination = $data['destination'];
$source = $data['source'];
$status = $data['status'] ?? 'success';
$created_at = date('Y-m-d H:i:s');

// Prevent duplicate tx_hash
$checkSql = "SELECT COUNT(*) FROM transactions WHERE tx_hash = :tx_hash";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bindValue(':tx_hash', $tx_hash, PDO::PARAM_STR);
$checkStmt->execute();

if ($checkStmt->fetchColumn() > 0) {
    echo json_encode(["success" => false, "message" => "Duplicate transaction hash."]);
    exit();
}

// Insert new transaction
$sql = "INSERT INTO transactions (tx_hash, amount, destination, source, status, created_at, user)
        VALUES (:tx_hash, :amount, :destination, :source, :status, :created_at, :user)";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':tx_hash', $tx_hash);
    $stmt->bindValue(':amount', $amount);
    $stmt->bindValue(':destination', $destination);
    $stmt->bindValue(':source', $source);
    $stmt->bindValue(':status', $status);
    $stmt->bindValue(':created_at', $created_at);
    $stmt->bindValue(':user', $Admin);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Transaction saved successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error."]);
    }

    $stmt = null;
    $conn = null;
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
