<?php 
require "../../Defaults/csrf.php";
require "../../Defaults/connect.php";
require "../../Defaults/account.php";

if (!isset($_POST["csrf_token"])||!validateToken($_POST["csrf_token"])){
	echo json_encode(["success"=>false,"error"=>"Invalid CSRF token"]);
	exit();
}

if (!isset($_POST['X'])||!isset($_POST['Y'])||!isset($_POST['color'])|| !$userinfo = getAccountInfo($conn)){
	echo json_encode(["success"=>false,"error"=>"Invalid request"]);
	exit();
}
function increasePoints($userId,$conn,$points){
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
function movePixel($conn,$X,$Y,$time,$table){
    $sql = "INSERT INTO `deleted` (`userid`, `X`, `Y`,`color`,`dateAdded`,`dateDeleted`,`adminDeleted`,`level`)
    SELECT `userid`, `X`, `Y`,`color`, `time`,?, 0,?
    FROM $table
    WHERE `X` = ? AND Y = ?"; // Add your condition here
    $stmt = prepared_query($conn,$sql,[$time,$table,$X,$Y],"ssii");
    mysqli_stmt_close($stmt);
    $sql = "DELETE FROM $table WHERE `X` = ? AND Y = ?"; // Add your condition here
    $stmt = prepared_query($conn,$sql,[$X,$Y],"ii");
    mysqli_stmt_close($stmt);
    
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
function isValidColor($colorCode) {
    if (preg_match('/^([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $colorCode)) {
      return true;
    } else {
      return false;
    }
  }
$color = $_POST['color'];
$X = $_POST['X'];
$Y = $_POST['Y'];


if ($X < 0 || $X > 100 || $Y < 0 || $Y > 100){
    echo json_encode(["success"=>false,"error"=>"Invalid coordinates"]);
    exit();
}
if (!isValidColor($color)){
    echo json_encode(["success"=>false,"error"=>"Invalid color"]);
    exit();
}



$chosenTable = 'canvas';
$myid = $userinfo["userid"];

$pixelCount = checkPixelCount($conn,$myid);
if ($pixelCount >= 100){
    echo json_encode(["success"=>false,"error"=>"You have reached the maximum number of pixels you can place."]);
    exit();
}
$timern = date('Y-m-d H:i:s',time());
movePixel($conn,$X,$Y,$timern,$chosenTable);
increasePoints($myid,$conn,1);

$sql = "INSERT INTO $chosenTable (`userid`,`X`,`Y`,`color`,`time`) VALUES (?,?,?,?,?)";
prepared_query($conn,$sql,[$myid,$X,$Y,$color,$timern],'iiiss');
echo json_encode(3);

?>