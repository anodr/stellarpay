<?php
ob_start();
session_start();

try {
    require_once('../includes/DB.php');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST) && !empty($_POST)) {
        $CurrentTime = time();
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $DateTime = strftime('%Y-%m-%d %H:%M:%S', $CurrentTime);

        if (isset($_SESSION['login'])) {
            $Admin = $_SESSION['username'];
        }
		
		$agentCode = htmlspecialchars($_POST['depositAgent']);

$stmt = $conn->prepare("SELECT stellarPublicKey FROM users WHERE agentCode = ?");
$stmt->execute([$agentCode]);

if ($stmt->rowCount() == 0) {
    $_SESSION["ErrorMessage"] = "Invalid Agent Code!";
    header("location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$agentRow = $stmt->fetch(PDO::FETCH_ASSOC);
$stellarAddress = $agentRow['stellarPublicKey'];

        $sql = "INSERT INTO deposits (amountDeposit, depositAgent, datedeposits, depositsby) 
		        VALUES (:amountDeposit, :depositAgent, :datedeposits, :depositsby)";
        $result = $conn->prepare($sql);
        $result->bindParam(':amountDeposit', htmlspecialchars($_POST['amountDeposit']), PDO::PARAM_STR);
		$result->bindParam(':depositAgent', $stellarAddress, PDO::PARAM_STR);
        $result->bindParam(':datedeposits', htmlspecialchars($DateTime), PDO::PARAM_STR);
        $result->bindParam(':depositsby', htmlspecialchars($Admin), PDO::PARAM_STR);
        $res = $result->execute();

        if ($res) {
            $_SESSION["SuccessMessage"] = "Deposit Done Successfully";
            header('location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            $_SESSION["ErrorMessage"] = "Something went wrong. Try again";
            header("location: index.php");
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    $_SESSION["SuccessMessage"] = $error;
    header('location: ' . $_SERVER['HTTP_REFERER']);
}

if (isset($error)) {
    echo "Errors: " . $error;
}

var_dump($res);

// echo $result . " Records Inserted in DB" . $db->lastInsertID();

function random_strings($length_of_string) {
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($str_result), 0, $length_of_string);
}
?>
