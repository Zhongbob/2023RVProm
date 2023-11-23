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

if (!isset($_POST["guestId"]) || !isset($_POST["vote"])){
    echo json_encode(["success"=>false,"error"=>"Critical Error. Reload the Page or contact rdevcca@gmail.com."]);
    exit();
}
if (!isset($_POST["section"]) || $_POST["section"] >= 3 || $_POST["section"] < 0){
    echo json_encode(["success"=>false,"error"=>"Section Undefined. Please reload the Page or contact rdevcca@gmail.com."]);
    exit();
}

$section = $_POST["section"];
$guestId = $_POST["guestId"];
$vote = $_POST["vote"]; // 1 = create, 0 = delete
if ($vote == 1){
    $sql = "SELECT COUNT(*) as peopleNominated FROM `votes` WHERE `voterId` = ? AND `category` = ?";
    $stmt = prepared_query($conn, $sql, [$userInfo["userid"],$section], "ii");
    $res = iimysqli_stmt_get_result($stmt);
    $row = iimysqli_result_fetch_assoc_array($res);
    if ($row["peopleNominated"] >= 3){
        echo json_encode(["success"=>false,"error"=>"You have already nominated 3 people for this category."]);
        exit();
    } 
    mysqli_stmt_close($stmt);
    $sql = "INSERT INTO `votes` (`guestId`,`voterId`,`category`) VALUES (?,?,?)";
    $res = prepared_query($conn,$sql,[$guestId,$userInfo["userid"],$section],"iii");
    if ($res === false) {
        echo json_encode(["success" => false, "error" => mysqli_error($conn) + " Please contact rdevcca@gmail.com."]);
        exit();
    }
    echo json_encode(["success"=>true]);
}
else if ($vote == 0){
    $sql = "DELETE FROM `votes` WHERE `guestId` = ? AND `voterId` = ? AND `category` = ?";
    $res = prepared_query($conn,$sql,[$guestId,$userInfo["userid"],$section],"iii");
    if ($res === false) {
        echo json_encode(["success" => false, "error" => mysqli_error($conn) + " Please contact rdevcca@gmail.com"]);
        exit();}
    echo json_encode(["success"=>true]);
}
else{
    echo json_encode(["success"=>false,"error"=>"vote out of range. Reload the Page or contact rdevcca@gmail.com"]);
}
?>