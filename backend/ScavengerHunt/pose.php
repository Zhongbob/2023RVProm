<?php
require "scavengerHunt.inc.php";

if (!isset($_POST["poseLink"])){
    error("Critical error. Please contact rdevcca.com@gmail.com");
}

$sql = "SELECT `userId` FROM `poseLink` WHERE `userId` = ?";
$stmt = prepared_query($conn, $sql, [$userInfo["userid"]], "i");
$res = iimysqli_stmt_get_result($stmt);
$row = iimysqli_result_fetch_assoc_array($res);
if ($row){
    error("You have already submitted a pose");
}
else{
    $sql = "INSERT INTO `poseLink` (`userId`,`link`) VALUES (?,?)";
    $res = prepared_query($conn,$sql,[$userInfo["userid"],$_POST["poseLink"]],"is");
    if ($res === false) {
        error(mysqli_error($conn) + " Please contact rdevcca@gmail.com");
    }
    increasePoints($userInfo["userid"],$conn,50);
    header("Location: ../../index.php?filename=scavengerhunt");
}

?>