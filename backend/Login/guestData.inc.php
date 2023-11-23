<?php

$name = str_ireplace('(rvhs)', '', $logInInfo["username"]);

$sql = "SELECT `guestId`,`class`,`studentName` FROM `guests`
        ORDER BY `class` ASC, `studentName` ASC";
$result = mysqli_query($conn, $sql);
$guestData = [];
$similarGuests = []; // Array to store guests with the most similar names
$mostSimilarScore = PHP_INT_MAX; // Initialize with the highest possible value

while ($row = mysqli_fetch_assoc($result)) {
    $class = $row["class"];
    if (!isset($guestData[$class])) {
        $guestData[$class] = [];
    }
    array_push($guestData[$class], [(int) $row["guestId"], $row["studentName"]]);

    // Update the most similar score if the current distance is smaller
    $levDistance = levenshtein($name, strtolower($row["studentName"]));

    
    if ($levDistance < $mostSimilarScore) {
        $mostSimilarScore = $levDistance;
        $similarGuests = [];
    }
    if ($levDistance == $mostSimilarScore){
        $similarGuests[] = [
            'guestId' => $row["guestId"],
            'class' => $row["class"],
            'studentName' => $row["studentName"],
        ];
    }

}

?>