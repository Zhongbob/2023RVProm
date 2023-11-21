<?php

$sql = "SELECT guests.`guestId`,`class`,`studentName`,`image`,`category` FROM `guests`
        JOIN `nominees` ON `guests`.`guestId` = `nominees`.`guestId`
        ORDER BY RAND()";
$result = mysqli_query($conn, $sql);
$guestData = [0 => [], 1 => [], 2 => []];
while ($row = mysqli_fetch_assoc($result)) {
    (int) $sectionId = $row["category"];
    (int)   $guestId = $row["guestId"];
    if (!isset($guestData[$sectionId])) {
        $guestData[$sectionId] = [];
    }
    $guestData[$sectionId][$guestId] = ["class" => $row["class"], "studentName" => $row["studentName"], "image" => $row["image"]];
}

?>