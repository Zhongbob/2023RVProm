<?php
require "../Defaults/connect.php";
require "../Defaults/account.php";
require "../Defaults/csrf.php";

if (!isset($_GET["csrf_token"])||!validateToken($_GET["csrf_token"])){
	echo json_encode(["success"=>false,"error"=>"Invalid CSRF token"]);
	exit();
}

if (!isset($_GET["scavengerHuntId"])|| !$userinfo = getAccountInfo($conn)){
	echo json_encode(["success"=>false,"error"=>"Invalid request"]);
	exit();
}

$scavengerHuntId = (int) $_GET["scavengerHuntId"];
$sql = "SELECT `scavengerHuntCode` FROM `scavengerhuntverficationcodes` WHERE `scavengerHuntId` = ?";
$stmt = prepared_query($conn,$sql,[$scavengerHuntId],"i");
$res = iimysqli_stmt_get_result($stmt);
$row = iimysqli_result_fetch_assoc_array($res);
mysqli_stmt_close($stmt);
echo json_encode(["success"=>true,"code"=>$row["scavengerHuntCode"]]);
?>