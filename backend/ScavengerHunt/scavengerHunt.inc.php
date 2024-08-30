<?php
function error($msg){
    echo "<script>alert('$msg');window.location.href = '../index.php';history.back()</script>";
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

function increasePointsIfNotVisited($conn,$userId,$scavengerHuntId,$points){
    $sql = "SELECT * FROM `visited` WHERE `userId` = ? AND `scavengerHuntId` = ?";
    $stmt = prepared_query($conn,$sql,[$userId,$scavengerHuntId],"ii");
    $res = iimysqli_stmt_get_result($stmt);
    $row = iimysqli_result_fetch_assoc_array($res);
    mysqli_stmt_close($stmt);
    if (!$row){
        increasePoints($conn,$userId,$points);
    }
    $sql = "INSERT IGNORE INTO `visited` (`userId`,`scavengerHuntId`) VALUES (?,?)";
    $stmt = prepared_query($conn,$sql,[$userId,$scavengerHuntId],"ii");
    mysqli_stmt_close($stmt);


}

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

$userInfo = getAccountInfo($conn);
if (!$userInfo){
    error("You are not logged in. Please log in.");
}
?>