<?php
//session_start();

require_once("../includes/DB.php");

$code = isset($_GET['agentCode']) ? $_GET['agentCode'] : '';

try {
    $db = new PDO("mysql:host=localhost;dbname=stellarPesa", "root", "");

    $stmt = $db->prepare("SELECT username, stellarPublicKey, agentCode FROM users WHERE agentCode = ?");
    $stmt->execute([$code]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($data ?: ["username" => "Not found", "stellarPublicKey" => ""]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>