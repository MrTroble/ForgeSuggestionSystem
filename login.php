<?php
if(isset($_GET) && isset($_GET["logout"])){
    unset($_COOKIE['token']);
    setcookie('token', null, -1, '/');
} else {
    if(!isset($_GET) || !isset($_GET["code"])){
        header("Location: https://github.com/login/oauth/authorize?client_id=68f267e2a51c384bff92");
        die();
    }
    $code = $_GET["code"];
    $file = fopen("creds.txt", "r");
    $secret = fgets($file);
    fclose($file);
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://github.com/login/oauth/access_token");
    curl_setopt($curl, CURLOPT_POST , 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "client_id=68f267e2a51c384bff92&client_secret=$secret&code=$code");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $rtn = curl_exec($curl);
    $rsp = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    if($rtn === false || $rsp >= 400){
        die();
    }
    $rtn = explode("=", $rtn)[1];
    $token = explode("&", $rtn)[0];

    curl_close($curl);

    $hmc = hash_hmac("sha1", (string)$token, $secret);
    $exp = time() + (60 * 60 * 24);
    setcookie("token", $hmc, $exp);

    $tokens = fopen("token.txt", "a");
    fwrite($tokens, "$token,$exp\n");
    fflush($tokens);
    fclose($tokens);
}
?>

<html>
    <head>
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Suggestion System</title>
    </head>
    <body>
        <h1>Login</h1>
        <div class="menu">
            <a href="./index.php">Home</a>
            <a href="./cache.html">List</a>
            <?php 
            if(isset($_COOKIE["token"])){
                echo "<a>You are logged in</a>";
            } else {
                echo "<a href='./login.php'>Login</a>";
            }
            ?>
        </div>
        <div class="container">
        <p>Done!</p>
        </div>
    </body>
</html>
