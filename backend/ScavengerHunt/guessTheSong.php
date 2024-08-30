<?php
require "../Defaults/connect.php";
require "../Defaults/account.php";
require "../Defaults/csrf.php";

if (!isset($_POST["csrf_token"])||!validateToken($_POST["csrf_token"])){
	echo json_encode(["success"=>false,"error"=>"Invalid CSRF token"]);
	exit();
}

if (!isset($_POST['X'])||!isset($_POST['Y'])||!isset($_POST['color'])|| !$userinfo = getAccountInfo($conn)){
	echo json_encode(["success"=>false,"error"=>"Invalid request"]);
	exit();
}

?>