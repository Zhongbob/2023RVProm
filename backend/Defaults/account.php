<?php
function getAccountInfo($conn,$redirect = false)
{
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['userid'])) {
        $sql = "SELECT `email`,`username`,`fromrvhs` FROM `users` WHERE `userId` = ?";
        $cursor = prepared_query($conn, $sql, [$_SESSION['userid'],], 'i');
        $cursor->bind_result($email, $username, $fromRVHS);
        if (!($cursor->fetch()))
            return false;
        return ["userid"=>$_SESSION['userid'],
                "email"=>$email, 
                "username"=>$username, 
                "fromRVHS"=>$fromRVHS];
    } else {
        if (rememberMe($conn))
            return getAccountInfo($conn);
        else{
            if ($redirect) {
                header("Location: index.php");
                exit();
            }
            return false;
        }
    }
    ;
}

function isAdmin($conn, $userinfo)
{
    if ($userinfo) {
        $sql = "SELECT EXISTS (
            SELECT 1 FROM admin WHERE email = ?
        ) AS 'exists';
        ";
        $cursor = prepared_query($conn, $sql, [$userinfo["email"],], 's');
        $cursor->bind_result($exists);
        $cursor->fetch();
        if ($exists)
            return true;
    }
    return false;
}
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

function storeTokenForUser($conn, $user, $token)
{
    $token = hash_hmac("sha256", $token, salt);
    $SQL = 'INSERT INTO `tokens`(`token`,`userid`) VALUES (?,?)';
    $stmt = prepared_query($conn, $SQL, [$token, $user], "si");
    if (!$stmt)
        return false;
    mysqli_stmt_close($stmt);
    $SQL = "SELECT MAX(`tokenid`) FROM `tokens` WHERE `token` = ? AND `userid` = ?";
    $stmt = prepared_query($conn, $SQL, [$token, $user], "si");
    if (!$stmt)
        return false;
    $res = iimysqli_stmt_get_result($stmt);
    $tokenid = iimysqli_result_fetch_array($res)[0];
    return $tokenid;
}

function fetchTokenByUserName($conn, $user, $tokenid)
{
    $SQL = 'SELECT `token` FROM `tokens` WHERE `userid` = ? AND `tokenid` = ?';
    $stmt = prepared_query($conn, $SQL, [$user, $tokenid], "si");
    if (!$stmt)
        return false;
    $res = iimysqli_stmt_get_result($stmt);
    if (!$res)
        return false;
    $res2 = iimysqli_result_fetch_array($res);
    if (!$res2)
        return false;
    $token = $res2[0];
    mysqli_stmt_close($stmt);
    return $token;
}

function destroyCookie($conn)
{

    $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';

    // print_r($_COOKIE);
    if (!$cookie)
        return false;
    list($user, $token, $tokenid, $mac) = explode(':', $cookie);
    if (!hash_equals(hash_hmac('sha256', $user . ':' . $token . ':' . $tokenid, SECRET_KEY), $mac))
        return false;

    $SQL = 'DELETE FROM `tokens` WHERE `userid` = ? AND `tokenid` = ?';
    $stmt = prepared_query($conn, $SQL, [$user, $tokenid], "ii");
    mysqli_stmt_close($stmt);
    setcookie("rememberme", "", time() - 3600, "/");
}

function rememberMe($conn)
{

    $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if ($cookie) {
        list($user, $token, $tokenid, $mac) = explode(':', $cookie);
        if (!hash_equals(hash_hmac('sha256', $user . ':' . $token . ':' . $tokenid, SECRET_KEY), $mac)) {
            return false;
        }
        $usertoken = fetchTokenByUserName($conn, $user, $tokenid);
        if (!$usertoken)
            return;
        if (hash_equals($usertoken, hash_hmac("sha256", $token, salt))) {
            $_SESSION["logged_in"] = true;
            $_SESSION["userid"] = $user;
            return true;
        } else {
            return false;
        }
    }

}

function getGuestInfo($conn,$userId){
    $sql = "SELECT `guests`.`guestId`,`class`,`studentName`,`tableNo`,`meal` FROM `guests`
            JOIN `guestuserjunction` ON `guestuserjunction`.`guestId` = `guests`.`guestId`
            WHERE `userId` = ?";
    $cursor = prepared_query($conn, $sql, [$userId,], 'i');
    $cursor->bind_result($guestId,$class,$studentName,$tableNo,$meal);
    if (!($cursor->fetch()))
        return false;
    return [
            "class"=>$class, 
            "studentName"=>$studentName, 
            "tableNo"=>$tableNo,
            "meal"=>$meal,
            "guestId"=>$guestId];
}
?>