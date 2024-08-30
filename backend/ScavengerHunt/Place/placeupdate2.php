<?php
set_time_limit(0);
session_write_close();
require "../../Defaults/connect.php";
require "../../Defaults/account.php";
require "../../Defaults/csrf.php";

// Set the time limit to infinite so PHP doesn't time out during the loop.

function emptyObj($object) {
    // Iterate through each property of the object
    foreach ($object as $property) {
        // If the property is not empty, return FALSE
        if (!empty($property)) {
            return false;
        }
    }
    // If none of the properties are empty, return TRUE
    return true;
}

function fetchData($conn, $id, $table){
	// Fetch the data from the database.
	$sql = "SELECT X, Y, color, u.username, (SELECT MAX(id) FROM $table) AS max_id
        FROM $table t
        INNER JOIN `users` u ON t.userid = u.userId
        WHERE t.id > ?";
	$stmt = prepared_query($conn,$sql,[$id],'i');
	$stmt -> bind_result($X,$Y,$color,$user,$max);
	$lstofitems = array('X'=>array(),'Y'=>array(),'color'=>array(),'user'=>array(),'prevID'=>0);

	while ($stmt -> fetch()){
		array_push($lstofitems['X'],(int) $X);
		array_push($lstofitems['Y'],(int) $Y);
		array_push($lstofitems['color'],$color);
		array_push($lstofitems['user'],$user);
		$lstofitems['prevID'] = (int) $max;
	}
	return $lstofitems;
}

function load($conn,$prevID, $table){
    $data = fetchData($conn,$prevID,$table);
    if (emptyObj($data)){
        // If there is no new data, sleep for 2 second and then try again.
        sleep(2);
        return load($conn,$prevID, $table);
    } else {
        // If there is new data, return it to the client.
        return $data;
    }
}

if (!isset($_GET["csrf_token"])||!validateToken($_GET["csrf_token"])){
	echo json_encode(0);
	exit();
}
if (!isset($_GET["prevID"]) || !$userinfo = getAccountInfo($conn)){
	echo json_encode(1);
	exit();
}


$prevID = $_GET["prevID"];
// check if prevID is integer
if (!is_numeric($prevID)){
    echo json_encode(1);
    exit();
}

$prevID = (int) $prevID;
$data = fetchData($conn,$prevID,"canvas");
// echo $data;
// Return the new data to the client.
echo json_encode($data);
?>
