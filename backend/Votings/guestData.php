<?php
require_once "../Defaults/connect.php";
$sql = "SELECT `guestId`,`class`,`studentName` FROM `guests`
        ORDER BY `class` ASC, `studentName` ASC";
$result = mysqli_query($conn, $sql);
$guestData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $class = $row["class"];
    if (!isset($guestData[$class])) {
        $guestData[$class] = [];
    }
    array_push($guestData[$class], [(int) $row["guestId"], $row["studentName"]]);
}

echo json_encode($guestData);
?>