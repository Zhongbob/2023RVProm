<?php
session_start();
$_SESSION = array();
session_destroy();
setcookie('rememberme', '', time() - 3600, '/');
header("Location: ../../index.php");
?>