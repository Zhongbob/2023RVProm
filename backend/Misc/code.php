<?php
require "../Defaults/connect.php";

$sql = "SELECT `scavengerHuntCode`,`scavengerHuntId` FROM `scavengerhuntverficationcodes`";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $code = $row["scavengerHuntCode"];
    $scavengerHuntId = $row["scavengerHuntId"];
    echo "https://rvprom2023.x10.mx/index.php?filename=scavengerhunt&code=$code&scavengerHuntId=$scavengerHuntId<br>";
}
?>