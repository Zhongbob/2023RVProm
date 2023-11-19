<?php
require "../Defaults/connect.php";
function GenerateRandomToken($length = 32)
{
    if (!isset($length) || intval($length) <= 8) {
        $length = 32;
    }
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes($length));
    }
    if (function_exists('mcrypt_create_iv')) {
        return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
    }
    if (function_exists('openssl_random_pseudo_bytes')) {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}

$sql = "SELECT `guestId` FROM guests";
$result = mysqli_query($conn, $sql);
$guests = [];
while ($row = mysqli_fetch_assoc($result)) {
    $guests[] = $row["guestId"];
}

$sql = "INSERT INTO `guestcodes` (`guestId`, `guestCode`) VALUES (?, ?)";
foreach ($guests as $guest) {
    $code = GenerateRandomToken(64);
    $stmt = prepared_query($conn, $sql, [$guest, $code], "is");
    if (!$stmt)
        return false;
    mysqli_stmt_close($stmt);
}
?>