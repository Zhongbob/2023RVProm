<?php

$sql = "SELECT guests.`guestId`,`class`,`studentName`,`image`,`category`,`nomineeDesc` FROM `guests`
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
    $guestData[$sectionId][$guestId] = ["voted" => false, "class" => $row["class"], "studentName" => $row["studentName"], "image" => $row["image"], "description" => $row["nomineeDesc"]];
}

$sql = "SELECT `guestId`,`category` FROM `votes` WHERE `voterId` = ?";
$stmt = prepared_query($conn, $sql, [$_SESSION["userid"]], "i");
$res = iimysqli_stmt_get_result($stmt);
while ($row = iimysqli_result_fetch_assoc_array($res)) {
    (int) $sectionId = $row["category"];
    (int)   $guestId = $row["guestId"];
    if (!isset($guestData[$sectionId])) {
        $guestData[$sectionId] = [];
    }
    if (isset($guestData[$sectionId][$guestId])) {
        $guestData[$sectionId][$guestId]["voted"] = true;
    }
}
?>