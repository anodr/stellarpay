<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include('../includes/DB.php');

try {
    // Handle preflight request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0);
    }

    // Validate POST method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST requests are allowed', 405);
    }

    // Get and validate input
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        throw new Exception('Invalid JSON input', 400);
    }
	
	if (isset($_SESSION['login'])) {
         $Admin = $_SESSION['username'];
        }

    // Required fields validation
    $required = [
        'tx_hash',
        'amount_xlm',
        'net_amount_xlm',
        'agent_fee_xlm',
        'system_fee_xlm',
        'destination',
        'agent_public_key',
        'source_account',
        'status'
    ];

    foreach ($required as $field) {
        if (!isset($input[$field])) {
            throw new Exception("Missing required field: $field", 400);
        }
    }

    // Numeric validation
    $numericFields = [
        'amount_xlm' => 7,
        'net_amount_xlm' => 7,
        'agent_fee_xlm' => 7,
        'system_fee_xlm' => 7
    ];

    foreach ($numericFields as $field => $precision) {
        if (!is_numeric($input[$field])) {
            throw new Exception("Invalid numeric value for $field", 400);
        }
        $input[$field] = number_format((float)$input[$field], $precision, '.', '');
    }

    // Stellar public key validation
    $stellarKeys = ['destination', 'agent_public_key', 'source_account'];
    foreach ($stellarKeys as $key) {
        if (!preg_match('/^G[0-9A-Z]{55}$/', $input[$key])) {
            throw new Exception("Invalid Stellar public key format for $key", 400);
        }
    }

    // Transaction hash validation
    if (!preg_match('/^[a-f0-9]{64}$/', $input['tx_hash'])) {
        throw new Exception('Invalid transaction hash format', 400);
    }

    // Check for duplicate transaction
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM transactions WHERE tx_hash = ?");
    $checkStmt->execute([$input['tx_hash']]);
    if ($checkStmt->fetchColumn() > 0) {
        throw new Exception('Duplicate transaction hash', 409);
    }

    // Insert transaction
    $stmt = $conn->prepare("
        INSERT INTO transactions (
            tx_hash,
            amount_xlm,
            net_amount_xlm,
            agent_fee_xlm,
            system_fee_xlm,
            destination,
            agent_public_key,
            source_account,
            status,
            created_at,
			user
        ) VALUES (
            :tx_hash,
            :amount_xlm,
            :net_amount_xlm,
            :agent_fee_xlm,
            :system_fee_xlm,
            :destination,
            :agent_public_key,
            :source_account,
            :status,
            NOW(),
			:user
        )
    ");

    $stmt->execute([
        ':tx_hash' => $input['tx_hash'],
        ':amount_xlm' => $input['amount_xlm'],
        ':net_amount_xlm' => $input['net_amount_xlm'],
        ':agent_fee_xlm' => $input['agent_fee_xlm'],
        ':system_fee_xlm' => $input['system_fee_xlm'],
        ':destination' => $input['destination'],
        ':agent_public_key' => $input['agent_public_key'],
        ':source_account' => $input['source_account'],
        ':status' => $input['status'],
		':user' => $Admin
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Transaction saved successfully',
        'tx_hash' => $input['tx_hash']
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log('Database Error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code($e->getCode() >= 400 ? $e->getCode() : 500);
    error_log('Application Error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}