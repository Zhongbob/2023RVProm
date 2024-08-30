<?php 
require "../Defaults/connect.inc.php";
if ($logininfo = verify_login($conn)){
    $id = (int) $_SESSION['id'];
    $sql = "SELECT `points` FROM `accounts` WHERE `id` = ?";
    $stmt =  prepared_query($conn,$sql,[$id],"i");
    $stmt -> bind_result($count);
    $stmt ->fetch();
    // $count = (int) mysqli_fetch_assoc()['points'];
    echo json_encode($count);
}
else{
    echo json_encode("pleaselogin");
}

?>