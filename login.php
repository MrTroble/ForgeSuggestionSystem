<?php
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
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$rtn = curl_exec($curl);
curl_close($curl);

if($rnt !== false){
    $token = str_replace("access_token=", "", explode("&", $rnt)[0]);
    $exp = time() + 60 * 60 * 24;
    setcookie("token", $token, $exp);
    echo $token;
    echo $exp;
    $tokens = fopen("token.txt", "a");
    fwrite("$token, $exp\n");
    fclose($tokens);
    echo "<h1>DONE! <a href='index.php'>Go back</a></h1>";
}
?>