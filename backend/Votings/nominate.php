<?php
require_once("../Defaults/connect.php");
require_once("../Defaults/account.php");
require_once("../Defaults/csrf.php");


function uploadImage($file,$section){
    $sectionName = ["partners-in-crime","prom-king","prom-queen","best-dressed-male","best-dressed-female"];
    $target_dir = "../../static/assets/nominees/".$sectionName[$section]."/";
    $baseName = basename($file["name"]);
    $target_file = $target_dir . $baseName;
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $fileMimeType = $finfo->file($file["tmp_name"]);
    error_log($_FILES['image']['error']);
    $check = getimagesize($file["tmp_name"]);
    $counter = 1;
    if($check === false) {
        echo json_encode(["success"=>false,"error"=>"File is not an image."]);
        exit();
    }

    while (file_exists($target_file)) {
        $target_file = $target_dir . $baseName . "_" . $counter . '.jpg';
        $counter++;
    }

    if ($file["size"] > 15728640) {
        echo json_encode(["success"=>false,"error"=>"Sorry, your file is too large. Keep Images under 15MB"]);
        exit();
    }

    if(!in_array($fileMimeType, ['image/jpeg', 'image/png'])) {
        echo json_encode(["success"=>false,"error"=>"Only JPG, JPEG, PNG files are allowed."]);
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
if (!$userInfo || !$userInfo["fromRVHS"]){
    echo json_encode(["success"=>false,"error"=>"You are not logged in. Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}

if (!isset($_POST["guestId1"]) || !isset($_POST["nomineeDesc"])){
    echo json_encode(["success"=>false,"error"=>"Seems that some fields are not field in. If you think this is an error, Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}
if (!isset($_POST["section"]) || $_POST["section"] >= 5 || $_POST["section"] < 0){
    echo json_encode(["success"=>false,"error"=>"Section Undefined. Please reload the Page or contact rdevcca@gmail.com."]);
    exit();
}

$image = "";
$guestId1 = $_POST["guestId1"];
$guestId2 = 0;
$section = $_POST["section"];
if (isset($_FILES["image"])){
    $image = uploadImage($_FILES["image"],$section);
}
if ($section == 0){
    if (!isset($_POST["guestId2"])){
        echo json_encode(["success"=>false,"error"=>"Seems that some fields are not field in. If you think this is an error, Reload the Page or contact rdevcca@gmail.com."]);
        exit();
    }
    $guestId2 = $_POST["guestId2"];
}
$sql = "SELECT COUNT(*) as peopleNominated FROM `votes` WHERE `voterId` = ? AND `category` = ?";
$stmt = prepared_query($conn, $sql, [$userInfo["userid"],$section], "ii");
$res = iimysqli_stmt_get_result($stmt);
$row = iimysqli_result_fetch_assoc_array($res);
if ($row["peopleNominated"] >= 3){
    echo json_encode(["success"=>false,"error"=>"You have already nominated 3 people for this category."]);
    exit();
}
mysqli_stmt_close($stmt);

$sql = "INSERT INTO `nominees` (`guestId1`,`guestId2`,`category`,`image`,`nomineeDesc`,`nominatorId`) VALUES (?,?,?,?,?,?)";
$cursor = prepared_query($conn,$sql,[$guestId1,$guestId2,$section,$image,$_POST["nomineeDesc"],$userInfo["userid"]],"iisssi");
if ($cursor === false) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn) + " Please contact rdevcca@gmail.com."]);
    exit();
}

$sql = "INSERT IGNORE INTO `votes` (`guestId1`,`guestId2`, `voterId`, `category`) VALUES (?, ?, ?,?)";
$res = prepared_query($conn,$sql,[$guestId1,$guestId2,$userInfo["userid"],$section],"iiii");

echo json_encode(["success"=>true]);
?>