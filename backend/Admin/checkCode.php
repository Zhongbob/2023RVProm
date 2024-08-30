<?php
require '../Defaults/connect.php';
require '../Defaults/account.php';
require '../Defaults/csrf.php';
if (!isset($_GET["csrf_token"])||!validateToken($_GET["csrf_token"])){
	echo json_encode(["success"=>false,"error"=>"Invalid CSRF token. Reload the Page or contact rdevcca@gmail.com."]);
	exit();
}
$userInfo = getAccountInfo($conn);
$isAdmin = isAdmin($conn, $userInfo);

if (!$userInfo || !$isAdmin){
    echo json_encode(["success"=>false,"error"=>"You are not logged in, or not a verified admin. Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}
if (!isset($_GET["guestId"]) || !is_numeric($_GET["guestId"])){
    echo json_encode(["success"=>false,"error"=>"Please a valid option."]);
    exit();
}

if (!isset($_GET["code"])){
    echo json_encode(["success"=>false,"error"=>"Something went wrong with your request. Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}

$code = $_GET["code"];
$guestId = $_GET["guestId"];
$sql = "SELECT `guestCode` FROM `guestcodes` WHERE `guestId` = ?";
$cursor = prepared_query($conn,$sql,[$guestId],"i");
if ($cursor === false) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn) + " Please contact rdevcca@gmail.com"]);
    exit();
}
$result = iimysqli_stmt_get_result($cursor);
$row = iimysqli_result_fetch_assoc_array($result);
mysqli_stmt_close($cursor);
if ($row === false){
    echo json_encode(["success" => false, "error" => "Invalid guest ID."]);
    exit();
}

if ($row["guestCode"] != $code){
    echo json_encode(["success" => false, "error" => "Invalid code."]);
    exit();
}

$sql = "INSERT IGNORE INTO `attendance` (`guestId`) VALUES (?)";
$cursor = prepared_query($conn,$sql,[$guestId],"i");
if ($cursor === false) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    exit();
}
mysqli_stmt_close($cursor);

$sql = "SELECT `studentName` FROM `guests` WHERE `guestId` = ?";
$cursor = prepared_query($conn,$sql,[$guestId],"i");
if ($cursor === false) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    exit();
}
$result = iimysqli_stmt_get_result($cursor);
$row = iimysqli_result_fetch_assoc_array($result);
mysqli_stmt_close($cursor);

echo json_encode(["success"=>true,"name"=>$row["studentName"]]);



?>