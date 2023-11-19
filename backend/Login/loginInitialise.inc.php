<?php
require_once "backend/google-api-php-client--PHP7.4/vendor/autoload.php";
require_once "backend/Login/googleLoginInfo.inc.php";
//creating client request to google
$client = new Google_Client();
$client->setClientId(clientID);
$client->setClientSecret(clientSecret);
$client->setRedirectUri(redirecturl);
$client->addScope("profile");
$client->addScope("email");
$googleUrl = $client->createAuthUrl();



?>