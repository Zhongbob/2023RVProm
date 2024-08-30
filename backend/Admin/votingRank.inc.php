<?php
if (!$isAdmin) exit();
$sql = "SELECT `guestId`,`class`,`studentName`,`tableNo` FROM `guests`";
$result = mysqli_query($conn, $sql);
$guestInfo = [];
while ($row = mysqli_fetch_assoc($result)) {
    $guestInfo[$row["guestId"]] = ["class" => $row["class"], "studentName" => $row["studentName"], "tableNo" => $row["tableNo"]];
}

$sql = "
SELECT s.`guestId1`,s.`guestId2`,s.`category`,`image`,`nomineeDesc`,`voteCount` FROM `nominees`
JOIN 
(SELECT `guestId1`,`guestId2`,category,COUNT(*) as voteCount FROM `votes` 
GROUP BY `guestId1`,`category`) s ON s.`guestId1` = `nominees`.`guestId1` AND s.guestId2 = nominees.guestId2 AND s.category = nominees.category
ORDER BY `voteCount` DESC";
$result = mysqli_query($conn, $sql);
$data = ['0'=>[],'1'=>[],'2'=>[],'3'=>[],'4'=>[]];
while ($row = mysqli_fetch_assoc($result)) {
    $data[$row["category"]][] = $row;
}
?>