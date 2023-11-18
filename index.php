<?php
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

switch ($filename) {
    case "voting":
        include('templates/voting.tpl.php');
        break;
    default:
        include('templates/home.tpl.php');
        break;
}
include('templates/defaults/end.tpl.php');

?>