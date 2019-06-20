<?php
$curl = curl_init();

$fields = array(
	'lname' => urlencode($_POST['last_name']),
);

curl_setopt($curl, CURLOPT_URL, "https://github.com/login/oauth/authorize?client_id=68f267e2a51c384bff92");
$result = curl_exec($ch);
curl_close($curl);

?>