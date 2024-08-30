<?php
require "../Defaults/connect.php";
require "../Defaults/account.php";
require "../Defaults/csrf.php";
require "increasePoints.php";
function curStage($conn,$userId,$scavengerHuntId){
    $sql = "INSERT IGNORE INTO `scavengerhuntquiz` (`userId`,`scavengerHuntId`) VALUES (?,?)";
    $stmt = prepared_query($conn,$sql,[$userId,$scavengerHuntId],"ii");
    mysqli_stmt_close($stmt);

    $sql = "SELECT `stage` FROM `scavengerhuntquiz` WHERE `userId` = ? AND `scavengerHuntId` = ?";
    $stmt = prepared_query($conn,$sql,[$userId,$scavengerHuntId],"ii");
    $res = iimysqli_stmt_get_result($stmt);
    $row = iimysqli_result_fetch_assoc_array($res);
    mysqli_stmt_close($stmt);
    return (int) $row["stage"];
}

function increaseStage($conn,$userId,$scavengerHuntId,$correct){
    $sql = "UPDATE `scavengerhuntquiz` SET `stage` = `stage` + 1 WHERE `userId` = ? AND `scavengerHuntId` = ?";
    $stmt = prepared_query($conn,$sql,[$userId,$scavengerHuntId],"ii");
    mysqli_stmt_close($stmt);
    if ($correct) increasePoints($conn,$userId,20);
}

if (!isset($_POST["csrf_token"])||!validateToken($_POST["csrf_token"])){
	echo json_encode(["success"=>false,"error"=>"Invalid CSRF token"]);
	exit();
}

if (!$userinfo = getAccountInfo($conn)){
	echo json_encode(["success"=>false,"error"=>"Please login"]);
	exit();
}

if (!isset($_POST["scavengerHuntId"]) || !isset($_POST["correct"])){
    echo json_encode(["success"=>false,"error"=>"Invalid request"]);
    exit();
}

$maxStages = [
    1=>3,
    5=>6,
    7=>1,
    8=>3,
];

$scavengerHuntId = (int) $_POST["scavengerHuntId"];
$stage = curStage($conn,$userinfo["userid"],$scavengerHuntId);
$correct = (bool) $_POST["correct"];
$maxStage = $maxStages[$scavengerHuntId];
if ($stage >= $maxStage){
    echo json_encode(["success"=>true,"completed"=>true,"stage"=>$stage]);
    exit();
}

increaseStage($conn,$userinfo["userid"],$scavengerHuntId,$correct);
echo json_encode(["success"=>true,"completed"=>false,"stage"=>$stage+1]);





?>