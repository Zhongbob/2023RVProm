<?php
define("CSRF_TOKEN_SECRET", 'WvjnzVHowfLrtFWHMbvuqBNZqeoRPwft');


session_start();




function createToken()
{
    $seed = GenerateRandomToken(32);
    $t = time();
    $hash = hash_hmac('sha256', session_id() . $seed . $t, CSRF_TOKEN_SECRET);
    return urlSafeEncode($hash . '|' . $seed . '|' . $t);
}



function urlSafeEncode($m)
{
    return rtrim(strtr(base64_encode($m), '+/', "-_"), '=');
}

function urlSafeDecode($m)
{
    return base64_decode(strtr(($m), '-_', "+/"));
}

function validateToken($token)
{
    $parts = explode('|', urlSafeDecode($token));
    if (count($parts) === 3) {
        $hash = hash_hmac('sha256', session_id() . $parts[1] . $parts[2], CSRF_TOKEN_SECRET);
        if (hash_equals($hash, $parts[0]))
            return true;
    }
    return false;
}
?>