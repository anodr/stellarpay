<?php
ob_start();
session_start();
?>

<?php
try {
    require_once('../includes/DB.php');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST) && !empty($_POST)) {
        $CurrentTime = time();
        date_default_timezone_set('Africa/Dar_es_Salaam');
        $DateTime = strftime('%Y-%m-%d %H:%M:%S', $CurrentTime);

        if (isset($_SESSION['login'])) {
            $Admin = $_SESSION['id'];
        }

        $sql = "INSERT INTO bidhaa (bidhaa, idadiChiniBidhaa, idadiJuuBidhaa, datebidhaa, bidhaaby) 
		        VALUES (:bidhaa, :idadiChiniBidhaa, :idadiJuuBidhaa, :datebidhaa, :bidhaaby)";
        $result = $conn->prepare($sql);
        $result->bindParam(':bidhaa', htmlspecialchars($_POST['bidhaa']), PDO::PARAM_STR);
		$result->bindParam(':idadiChiniBidhaa', htmlspecialchars($_POST['idadiChiniBidhaa']), PDO::PARAM_INT);
		$result->bindParam(':idadiJuuBidhaa', htmlspecialchars($_POST['idadiJuuBidhaa']), PDO::PARAM_INT);
        $result->bindParam(':datebidhaa', htmlspecialchars($DateTime), PDO::PARAM_STR);
        $result->bindParam(':bidhaaby', htmlspecialchars($Admin), PDO::PARAM_STR);
        $res = $result->execute();

        if ($res) {
            $_SESSION["SuccessMessage"] = "Bidhaa imeongezwa kikamilifu";
            header('location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            $_SESSION["ErrorMessage"] = "Something went wrong. Try again";
            header("location: index.php");
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    $_SESSION["SuccessMessage"] = "Bidhaa imeongezwa tayari !";
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
