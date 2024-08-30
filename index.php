<?php
require_once("backend/Defaults/connect.php");
require_once("backend/Defaults/account.php");
require_once("backend/Defaults/csrf.php");

$logInInfo = getAccountInfo($conn);
$guestInfo = !$logInInfo?false:getGuestInfo($conn,$logInInfo["userid"]); 
$isAdmin = isAdmin($conn, $logInInfo);
header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Content-Security-Policy: worker-src https:');

if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];
} else {
    $filename = "";
}


include('templates/defaults/header.tpl.php');

if ($logInInfo && !$guestInfo && !$isAdmin) {
    include('templates/guestSignUp.tpl.php');
} else {
    switch ($filename) {
        case "voting":
            if ($isAdmin){
                include('templates/voting.tpl.php');
                break;
            }
            else{
                include('templates/closed.tpl.php');
                break;
            }
        case "ticket":
            if ($isAdmin){
                include('templates/adminScanQr.tpl.php');
            }
            else{
                include('templates/ticket.tpl.php');
            }
            break;
        case "attendance":
            if ($isAdmin){
                include('templates/attendance.tpl.php');
            }
            break;
        case "votingleaderboard":
            if ($isAdmin){
                include('templates/leaderboard.tpl.php');
            }
            break;
        case "pointsleaderboard":
            if ($isAdmin){
                include('templates/leaderboardPoints.tpl.php');
            }
            break;
        case "addpoints":
            if ($isAdmin){
                include('templates/addPoints.tpl.php');
            }
            break;
        case "scavengerhunt":
            
            include('templates/scavengerHunt.tpl.php');
            break;
        case "privacypolicy":
            include('templates/privacyPolicy.tpl.php');
            break;
        default:
            include('templates/home.tpl.php');
            break;
    }
}

include('templates/defaults/end.tpl.php');

?>