<?php
$sql = "SELECT `guestId`,`class`,`studentName` FROM `guests`";
$result = mysqli_query($conn, $sql);
$guestInfo = [];
while ($row = mysqli_fetch_assoc($result)) {
    $guestInfo[$row["guestId"]] = ["class" => $row["class"], "studentName" => $row["studentName"]];
}


$sql = "SELECT `guestId1`,`guestId2`,`image`,`category`,`nomineeDesc` FROM `nominees`
        WHERE `selected` = 1
        ORDER BY RAND()
        ";

$result = mysqli_query($conn, $sql);
$guestData = [0 => [], 1 => [], 2 => [], 3 => [], 4 => []];
while ($row = mysqli_fetch_assoc($result)) {
    (int) $sectionId = $row["category"];
    (int) $guestId1 = $row["guestId1"];
    (int) $guestId2 = $row["guestId2"];
    if (!isset($guestData[$sectionId])) {
        $guestData[$sectionId] = [];
    }
    if ($guestId2 == 0) {
        $key = (string) $guestId1;
    } else {
        $key = (string) $guestId1 . "-" . (string) $guestId2;
    }
    $guestData[$sectionId][$key] = ["guestId1" => $guestId1, "guestId2" => $guestId2, 
                     "voted" => false, "image" => $row["image"], "description" => $row["nomineeDesc"]];
}
if ($logInInfo) {
    $sql = "SELECT `guestId1`,`guestId2`,`category` FROM `votes` WHERE `voterId` = ?";
    $stmt = prepared_query($conn, $sql, [$_SESSION["userid"]], "i");
    $res = iimysqli_stmt_get_result($stmt);
    while ($row = iimysqli_result_fetch_assoc_array($res)) {
        if ($row["guestId2"] == 0) {
            $key1 = (string) $row["guestId1"];
            $key2 = (string) $row["guestId1"];
        } else {
            $key1 = (string) $row["guestId1"] . "-" . (string) $row["guestId2"];
            $key2 = (string) $row["guestId2"] . "-" . (string) $row["guestId1"];
        }
        (int) $sectionId = $row["category"];
        if (!isset($guestData[$sectionId])) {
            $guestData[$sectionId] = [];
        }
        if (isset($guestData[$sectionId][$key1])) {
            $guestData[$sectionId][$key1]["voted"] = true;
        }
        if (isset($guestData[$sectionId][$key2])) {
            $guestData[$sectionId][$key2]["voted"] = true;
        }
    }
    mysqli_stmt_close($stmt);

    $maxNominations = 1;
    $sql = "SELECT COUNT(*) as peopleNominated ,`category`  FROM votes WHERE `voterId` = ? GROUP BY `category`";
    $stmt = prepared_query($conn, $sql, [$_SESSION["userid"]], "i");
    $remainingVotes = [1, 1, 1, 1, 1];
    $res = iimysqli_stmt_get_result($stmt);
    while ($row = iimysqli_result_fetch_assoc_array($res)) {
        $remainingVotes[$row["category"]] = $remainingVotes[$row["category"]] - $row["peopleNominated"];
    }
} else {
    $remainingVotes = [1, 1, 1, 1, 1];
}

?>