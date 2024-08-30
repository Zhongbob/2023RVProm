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

$sql = "INSERT IGNORE INTO `attendance` (`guestId`) VALUES (?)";
$cursor = prepared_query($conn,$sql,[$guestId],"i");
if ($cursor === false) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    exit();
}
mysqli_stmt_close($cursor);

$sql = "SELECT `studentName`,`tableNo` FROM `guests` WHERE `guestId` = ?";
$cursor = prepared_query($conn,$sql,[$guestId],"i");
if ($cursor === false) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
    exit();
}
$result = iimysqli_stmt_get_result($cursor);
$row = iimysqli_result_fetch_assoc_array($result);
mysqli_stmt_close($cursor);
$name = $row["studentName"];
$tableNo = $row["tableNo"];

$sql = "SELECT `email`,`fromrvhs` FROM `users`
JOIN `guestuserjunction` ON `users`.`userid` = `guestuserjunction`.`userid`
WHERE `guestId` = ?";
$result = prepared_query($conn,$sql,[$guestId],"i");
$result = iimysqli_stmt_get_result($result);
$row = iimysqli_result_fetch_assoc_array($result);
if ($row){
    echo json_encode(["success"=>true,"name"=>$name,"tableNo"=>$tableNo,"signedUp"=>true,"email"=>$row["email"],"fromrvhs"=>$row["fromrvhs"]]);
}
else{
    echo json_encode(["success"=>true,"name"=>$name,"tableNo"=>$tableNo,"signedUp"=>false]);
}
?>