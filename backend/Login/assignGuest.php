<?php
require_once("../Defaults/connect.php");
require_once("../Defaults/account.php");
require_once("../Defaults/csrf.php");
if (!isset($_POST["csrf_token"])||!validateToken($_POST["csrf_token"])){
	echo json_encode(["success"=>false,"error"=>"Invalid CSRF token. Reload the Page or contact rdevcca@gmail.com."]);
	exit();
}
$userInfo = getAccountInfo($conn);
if (!$userInfo){
    echo json_encode(["success"=>false,"error"=>"You are not logged in. Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}
if (!isset($_POST["guestId"])){
    echo json_encode(["success"=>false,"error"=>"Please a valid option."]);
    exit();
}

$sql = "INSERT INTO `guestuserjunction` (`guestId`,`userId`) VALUES (?,?)";
$cursor = prepared_query($conn,$sql,[$_POST["guestId"],$userInfo["userid"]],"ii");
if ($cursor === false) {
    echo json_encode(["success" => false, "error" => mysqli_error($conn) + " Please contact rdevcca@gmail.com."]);
    exit();
}

echo json_encode(["success"=>true]);
exit();
?>