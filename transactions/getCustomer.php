<?php
//session_start();

require_once("../includes/DB.php");

$code = isset($_GET['mobile']) ? $_GET['mobile'] : '';

if (empty($code)) {
    echo json_encode(["error" => "Mobile number is required"]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT username, stellarPublicKey, mobile FROM users WHERE mobile = ?");
    $stmt->execute([$code]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($data ?: ["username" => "Not found", "stellarPublicKey" => ""]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>