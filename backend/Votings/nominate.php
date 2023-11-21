<?php
require_once("../Defaults/connect.php");
require_once("../Defaults/account.php");
require_once("../Defaults/csrf.php");

function uploadImage($file,$section){
    $sectionName = ["partners-in-crime","prom-king","prom-queen"];
    $target_dir = "../../static/assets/nominees/".$sectionName[$section]."/";
    $baseName = basename($file["name"]);
    $target_file = $target_dir . $baseName;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($file["tmp_name"]);
    $counter = 1;
    if($check === false) {
        echo json_encode(["success"=>false,"error"=>"File is not an image."]);
        exit();
    }

    while (file_exists($target_file)) {
        $target_file = $target_dir . $baseName . "_" . $counter . '.' . $imageFileType;
        $counter++;
    }

    if ($file["size"] > 10000000) {
        echo json_encode(["success"=>false,"error"=>"File is too large."]);
        exit();
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo json_encode(["success"=>false,"error"=>"Only JPG, JPEG, PNG & GIF files are allowed."]);
        exit();
    }
    if (!move_uploaded_file($file["tmp_name"], $target_file)) {
        echo json_encode(["success"=>false,"error"=>"Sorry, there was an error uploading your file."]);
        exit();
    }
    return "static/assets/nominees/".$sectionName[$section]."/".basename($target_file);
}
if (!isset($_POST["csrf_token"])||!validateToken($_POST["csrf_token"])){
	echo json_encode(["success"=>false,"error"=>"Invalid CSRF token. Reload the Page or contact rdevcca@gmail.com."]);
	exit();
}
$userInfo = getAccountInfo($conn);
if (!$userInfo){
    echo json_encode(["success"=>false,"error"=>"You are not logged in. Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}

if (!isset($_POST["guestId"]) || !isset($_POST["nomineeDesc"])){
    echo json_encode(["success"=>false,"error"=>"Seems that some fields are not field in. If you think this is an error, Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}
if (!isset($_POST["section"]) || $_POST["section"] >= 3 || $_POST["section"] < 0){
    echo json_encode(["success"=>false,"error"=>"Section Undefined. Please reload the Page or contact rdevcca@gmail.com."]);
    exit();
}

$image = "";
$section = $_POST["section"];
if (isset($_FILES["image"])){
    $image = uploadImage($_FILES["image"],$section);
}

$sql = "INSERT INTO `nominees` (`guestId`,`category`,`image`,`nomineeDesc`,`nominatorId`) VALUES (?,?,?,?,?)";
$cursor = prepared_query($conn,$sql,[$_POST["guestId"],$section,$image,$_POST["nomineeDesc"],$userInfo["userid"]],"isssi");
if ($cursor === false) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn) + " Please contact rdevcca@gmail.com."]);
    exit();
}

echo json_encode(["success"=>true]);
?>