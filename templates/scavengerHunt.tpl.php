<?php
require_once "backend/ScavengerHunt/scavengerHunt.inc.php";
if (isset($_GET['code']) && isset($_GET['scavengerHuntId'])) {
    $code = $_GET['code'];
    $scavengerHuntId = (int) $_GET['scavengerHuntId'];
    $sql = "SELECT `scavengerHuntCode` FROM `scavengerhuntverficationcodes` WHERE `scavengerHuntCode` = ?";
    $stmt = prepared_query($conn, $sql, [$code], "s");
    $result = iimysqli_stmt_get_result($stmt);
    $row = iimysqli_result_fetch_assoc_array($result);
    mysqli_stmt_close($stmt);
    if ($code == $row["scavengerHuntCode"]) {
        $stage = curStage($conn,$userInfo["userid"],$scavengerHuntId);
        $instagramCompleted = isCompleted($conn,$userInfo["userid"],$scavengerHuntId);
        increasePointsIfNotVisited($conn,$userInfo["userid"],$scavengerHuntId,20);
        // Code verification successful
        switch ($scavengerHuntId){
            case 1:
                require("templates/Scavengerhunt/guessTheSong.tpl.php");
                break;
            case 2:
                require("templates/Scavengerhunt/place.tpl.php");
                break;
            case 3:
                require("templates/Scavengerhunt/pose.tpl.php");
                break;
            case 4:
                require("templates/Scavengerhunt/instagram.tpl.php");
                break;
            case 5:
                require("templates/Scavengerhunt/teacherQuiz.tpl.php");
                break;
            case 6:
                require("templates/Scavengerhunt/instagram2.tpl.php");
                break;
            case 7:
                require("templates/Scavengerhunt/code.tpl.php");
                break;
            case 8:
                require("templates/Scavengerhunt/schoolPlaces.tpl.php");
                break;
        }
    } else {
        // Code verification failed
        echo "<script>alert('Code verification failed. Please try again.');</script>";
        include('templates/scavengerHuntScan.tpl.php');
    }
} else {
    include('templates/scavengerHuntScan.tpl.php');
}
require("templates/defaults/nav.tpl.php")
?>