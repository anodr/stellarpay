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
            $Admin = $_SESSION['username'];
        }
		
$image = 'img' . $_FILES['File']['name'];
move_uploaded_file($_FILES['File']['tmp_name'], 'uploads/' . $_FILES['File']['name']);
$orgfile = 'uploads/' . $_FILES['File']['name'];
list($width, $height, $type) = getimagesize($orgfile); // Get the image type

switch ($type) {
    case IMAGETYPE_JPEG:
        $newfile = ImageCreateFromJpeg($orgfile);
        break;
    case IMAGETYPE_GIF:
        $newfile = imagecreatefromgif($orgfile);
        break;
    case IMAGETYPE_PNG:
        $newfile = imagecreatefrompng($orgfile);
        break;
    default:
        // Handle unsupported image type
        echo "Unsupported image type. Please upload a JPEG, GIF, or PNG image.";
        exit;
}

$String = random_strings(10);
$newthumb = 'uploads/' . $String . '' . $_FILES['File']['name'];
imagejpeg($newfile, $newthumb, 25); // Adjust quality to your preference

// Clean up resources
imagedestroy($newfile);

        $sql = "INSERT INTO company (companyName, companyEmail, companyPhone, TIN, VRN, companyAddress, branchUser, file, compressfile,  datecompany, companyby) 
		        VALUES (:companyName, :companyEmail, :companyPhone, :TIN, :VRN, :companyAddress, :branchUser, :File, :compressfile, :datecompany, :companyby)";
        $result = $conn->prepare($sql);
        $result->bindParam(':companyName', htmlspecialchars($_POST['companyName']), PDO::PARAM_STR);
        $result->bindParam(':companyEmail', htmlspecialchars($_POST['companyEmail']), PDO::PARAM_STR);
		$result->bindParam(':companyPhone', htmlspecialchars($_POST['companyPhone']), PDO::PARAM_STR);
		$result->bindParam(':TIN', htmlspecialchars($_POST['TIN']), PDO::PARAM_STR);
		$result->bindParam(':VRN', htmlspecialchars($_POST['VRN']), PDO::PARAM_STR);
        $result->bindParam(':companyAddress', htmlspecialchars($_POST['companyAddress']), PDO::PARAM_STR);
		$result->bindParam(':branchUser', htmlspecialchars($_POST['branchUser']), PDO::PARAM_STR);
		$result->bindParam(':File', htmlspecialchars($_FILES['File']['name']), PDO::PARAM_STR);
        $result->bindParam(':compressfile', htmlspecialchars($image), PDO::PARAM_STR);
        $result->bindParam(':datecompany', htmlspecialchars($DateTime), PDO::PARAM_STR);
        $result->bindParam(':companyby', htmlspecialchars($Admin), PDO::PARAM_STR);
        $res = $result->execute();

        if ($res) {
            $_SESSION["SuccessMessage"] = "Company added Successfully";
            header('location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            $_SESSION["ErrorMessage"] = "Something went wrong. Try again";
            header("location: index.php");
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    $_SESSION["SuccessMessage"] = "Company Already Added !";
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
