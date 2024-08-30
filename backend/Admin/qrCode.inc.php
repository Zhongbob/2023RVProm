<?php
$sql = "SELECT `guestId`,`class`,`studentName` FROM `guests`";
$result = mysqli_query($conn, $sql);
$guestInfo = [];
while ($row = mysqli_fetch_assoc($result)) {
    $guestInfo[$row["guestId"]] = ["class" => $row["class"], "studentName" => $row["studentName"]];
}
?>