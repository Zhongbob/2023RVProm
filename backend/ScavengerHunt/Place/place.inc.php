<?php
require "../../Defaults/connect.php";
require "../../Defaults/csrf.php";
require "../../Defaults/account.php";

if (!isset($_GET["csrf_token"])||!validateToken($_GET["csrf_token"])){
	echo json_encode(0);
	exit();
}
function checkPixelCount($conn,$userId){
    $sql = "SELECT SUM(count) FROM (
      SELECT COUNT(*) as count FROM `canvas` WHERE `userid` = ?
      UNION ALL
      SELECT COUNT(*) as count FROM `deleted` WHERE `userid` = ?
  ) AS subquery";
    $stmt = prepared_query($conn,$sql,[$userId,$userId],"ii");
    $result = iimysqli_stmt_get_result($stmt);
    $count = iimysqli_result_fetch_array($result)[0];
    mysqli_stmt_close($stmt);
    return $count;
}
$userinfo = getAccountInfo($conn);
$chosenTable = "canvas";
$sql = "SELECT X, Y, color, u.username, (SELECT MAX(id) FROM $chosenTable) AS max_id
FROM $chosenTable t
INNER JOIN `users` u ON t.userid = u.userId";
$res = mysqli_query($conn,$sql);
$lstofitems = array('X'=>array(),'Y'=>array(),'color'=>array(),'user'=>array(),'prevID'=>0,"pixelCount"=>checkPixelCount($conn,$userinfo['userid']));
while ($result = mysqli_fetch_assoc($res)){
	array_push($lstofitems['X'],(int) $result['X']);
	array_push($lstofitems['Y'],(int) $result['Y']);
	array_push($lstofitems['color'],(string) $result['color']);
	array_push($lstofitems['user'],$result['username']);
	$lstofitems['prevID'] = (int) $result['max_id'];
}

echo json_encode($lstofitems);
?>