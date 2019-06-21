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

echo "<h1>DONE! <a href='index.php'>Go back</a></h1>";
?>