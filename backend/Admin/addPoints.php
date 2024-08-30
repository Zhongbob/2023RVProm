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
if (!isset($_POST["guestId"]) || !is_numeric($_POST["guestId"]) || !isset($_POST["points"]) || !is_numeric($_POST["points"]) || $_POST["points"] < 0){
    echo json_encode(["success"=>false,"error"=>"Please a valid option."]);
    exit();
}

function increasePoints($conn,$userId,$points){
    $sql = "INSERT IGNORE INTO points (userId) VALUES (?)";
    $stmt = prepared_query($conn,$sql,[$userId],"i");
    mysqli_stmt_close($stmt);
    
    $sql = "UPDATE points SET points = points + ? WHERE userId = ?";
    $stmt = prepared_query($conn,$sql,[$points,$userId],"ii");
    if ($stmt === false) {
        error(mysqli_error($conn) + " Please contact rdevcca@gmail.com");
    }
    
    mysqli_stmt_close($stmt);
}


$points = $_POST["points"];
$guestId = $_POST["guestId"];

$sql = "SELECT `userId` FROM `guestuserjunction` WHERE `guestId` = ?";
$stmt = prepared_query($conn,$sql,[$guestId],"i");
$result = iimysqli_stmt_get_result($stmt);
$row = iimysqli_result_fetch_assoc_array($result);
if (!$row){
    echo json_encode(["success"=>false,"error"=>"Guest has not signed up."]);
    exit();
}
mysqli_stmt_close($stmt);
$userId = $row["userId"];
increasePoints($conn,$userId,$points);
echo json_encode(["success"=>true]);

?>
