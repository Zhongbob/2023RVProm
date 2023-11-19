<?php
require "../Defaults/connect.php";
require "../Defaults/csrf.php";
require "../Defaults/account.php";
if (!isset($_GET["csrf_token"])||!validateToken($_GET["csrf_token"])){
	echo json_encode(["success"=>false,"error"=>"Invalid CSRF token. Reload the Page or contact rdevcca@gmail.com."]);
	exit();
}
$userInfo = getAccountInfo($conn);
if (!$userInfo){
    echo json_encode(["success"=>false,"error"=>"You are not logged in. Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}

$guestInfo = getGuestInfo($conn,$userInfo["userid"]);
if (!$guestInfo){
    echo json_encode(["success"=>false,"error"=>"You have not registered. Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}
$sql = "SELECT `guestcode` FROM `guestcodes` WHERE `guestId`=?";
$stmt = prepared_query($conn, $sql, [$guestInfo["guestId"],], 'i');
$stmt->bind_result($guestCode);
if (!($stmt->fetch())){
    echo json_encode(["success"=>false,"error"=>"You have not registered. Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}
echo json_encode(["success"=>true,"code"=>$guestInfo["guestId"].":".$guestCode]);
?>