<?php
require_once "backend/google-api-php-client--PHP7.4/vendor/autoload.php";
require_once "backend/Defaults/connect.php";
require_once "backend/Defaults/account.php";
require_once "backend/Login/googleLoginInfo.inc.php";
//creating client request to google
$client = new Google_Client();
$client->setClientId(clientID);
$client->setClientSecret(clientSecret);
$client->setRedirectUri(redirecturl);
$client->addScope("profile");
$client->addScope("email");
$variables = [
    "level" => isset($_GET["level"])?$_GET["level"]:"sec1_2",
];
$state = json_encode($variables);
$client->setState($state);
$googleUrl = $client->createAuthUrl();
function onLogin($conn,$user) {
    // Function to set the cookie
    $_SESSION['userid'] = $user;
    print_r("userid:".$_SESSION['userid']);
    $token = GenerateRandomToken(128); // generate a token, should be 128 - 256 bit
    $tokenid = storeTokenForUser($conn, $user, $token);
    $cookie = $user . ':' . $token.':'.$tokenid;
    $mac = hash_hmac('sha256', $cookie, SECRET_KEY); //
    $cookie .= ':' . $mac;
    setcookie('rememberme', $cookie, [
        'expires'=>time() + (10 * 365 * 24 * 60 * 60),
        'path'=>"/",
        'secure'=>true,
        'httponly'=>true]);
}

if(isset($_GET['code'])){
    session_destroy();
    session_start();
    if(isset($_COOKIE['rememberme'])) {
        setcookie("rememberme", "", time()-3600,"/");
        // echo json_encode("Failure");
       }  
    // $_SESSION['logged_in'] = true;
    // $_SESSION[""]
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if ($token["error"]) echo "<script>window.location.href = 'index.php?filename=login' </script>";
    $client->setAccessToken($token);
    // google black magic
    $gauth = new Google_Service_Oauth2($client);
    $google_info = $gauth->userinfo->get();
    // Query the email from the google auth
    $email = $google_info->email;
    $name = $google_info->name;
    $picture = $google_info->picture;
    // Check if the email that google sends back exists
    if ($email){
        $exploded = explode(" ", rtrim($name, " "));
        $lastexploded = end($exploded);
        $fromrvhs = strtoupper($lastexploded) === "(RVHS)";
        if (($google_info['hd'] == "students.edu.sg" && $fromrvhs) || $google_info['hd'] == "moe.edu.sg") {
            // Check if the email exists in the database
            $sql = "SELECT `id` FROM users WHERE email = ?";
            $stmt = prepared_query($conn,$sql,[$email,],"s");
            $stmt->bind_result($userid);
            $exists = $stmt->fetch();
            // echo $exists;
            mysqli_stmt_close($stmt);
            $variables = json_decode($_GET["state"],true);
            $level = $variables["level"];
            if ($exists) {
                echo "Ran";
                onLogin($conn,$userid);
                header("Location: index.php?level=".$level);
                // use headers to redirect instead of javascript
            } else {
                // If the email does not exist, create a new user
                $sql = "INSERT INTO users (`email`, `name`) VALUES (?, ?)";
                $stmt = prepared_query($conn,$sql,[$email,$name],"ss");
                mysqli_stmt_close($stmt);

                $sql = "SELECT `id` FROM users WHERE email = ?";
                $stmt = prepared_query($conn,$sql,[$email],"s");
                $stmt->bind_result($userid);
                $exists = $stmt->fetch();
                mysqli_stmt_close($stmt);
                onLogin($conn,$userid);
                
                header("Location: index.php?level=".$level);
            }
        } else {
            echo "<script>alert('Sorry! Only emails with the students.edu.sg or moe.edu.sg domain name, and from RVHS can signup!');</script>";
            
        }
    }
}

?>