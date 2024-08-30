<?php

require '../Defaults/connect.php';
require '../Defaults/account.php';
require '../Defaults/csrf.php';
if (!isset($_POST["csrf_token"])||!validateToken($_POST["csrf_token"])){
	echo json_encode(["success"=>false,"error"=>"Invalid CSRF token. Reload the Page or contact rdevcca@gmail.com."]);
	exit();
}
$userInfo = getAccountInfo($conn);
$isAdmin = isAdmin($conn, $userInfo);

if (!$userInfo || !$isAdmin){
    echo json_encode(["success"=>false,"error"=>"You are not logged in, or not a verified admin. Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}
if (!isset($_POST["guestId"]) || !is_numeric($_POST["guestId"])){
    echo json_encode(["success"=>false,"error"=>"Please a valid option."]);
    exit();
}

$guestId = $_POST["guestId"];
$sql = "UPDATE `users` SET `fromrvhs` = 1 WHERE `userid` = (
    SELECT `userid` FROM `guestuserjunction` WHERE `guestId` = ?
)";
$cursor = prepared_query($conn,$sql,[$guestId],"i");
if ($cursor === false) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    exit();
}
mysqli_stmt_close($cursor);
echo json_encode(["success"=>true]);
?>