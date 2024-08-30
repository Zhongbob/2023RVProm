<?php
require "../Defaults/connect.php";
require "../Defaults/account.php";
require "../Defaults/csrf.php";
require "increasePoints.php";
function isCompleted($conn,$userId,$scavengerHuntId){
    $sql = "SELECT `userId` FROM `scavengerhuntlinks` WHERE `userId` = ? AND `scavengerHuntId` = ?";
    $stmt = prepared_query($conn,$sql,[$userId,$scavengerHuntId],"ii");
    $res = iimysqli_stmt_get_result($stmt);
    $row = iimysqli_result_fetch_assoc_array($res);
    if (!$row){
        return false;
    }
    return true;

}

function submitLink($conn,$userId,$scavengerHuntId,$link){
    $sql = "INSERT INTO `scavengerhuntlinks` (`userId`,`scavengerHuntId`,`link`) VALUES (?,?,?)";
    $stmt = prepared_query($conn,$sql,[$userId,$scavengerHuntId,$link],"iis");
    mysqli_stmt_close($stmt);
    increasePoints($conn,$userId,100);
}

if (!isset($_POST["csrf_token"])||!validateToken($_POST["csrf_token"])){
	echo json_encode(["success"=>false,"error"=>"Invalid CSRF token"]);
	exit();
}

if (!$userinfo = getAccountInfo($conn)){
	echo json_encode(["success"=>false,"error"=>"Please login"]);
	exit();
}

if (!isset($_POST["scavengerHuntId"]) || !isset($_POST["link"])){
    echo json_encode(["success"=>false,"error"=>"Invalid request"]);
    exit();
}
$completed = isCompleted($conn,$userinfo["userid"],$_POST["scavengerHuntId"]);
if ($completed){
    echo json_encode(["success"=>false,"error"=>"You have already completed this scavenger hunt"]);
    exit();
}
submitLink($conn,$userinfo["userid"],$_POST["scavengerHuntId"],$_POST["link"]);
echo json_encode(["success"=>true]);

?>