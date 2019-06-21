<?php
// parses csvs and packs them in arrays
function readAndPack($name){
    $map = array();
    $file = fopen($name, "r");
    if($file) {
        fgets($file); // Gets the first line bc not needed
        while(($line = fgets($file)) !== false){
            $arr = explode(",", $line);
            $map[$arr[0]] = $arr[1];
        }
        fclose($file);
    } else {
        echo "Error reading " . $name . "! Contact side owner!";
    }    
    return $map;
}

function checkCookie(){
    $token = $_COOKIE[token];
    if(!isset($token)) {
        return false;
    }
    $file = fopen("creds.txt", "r");
    $secret = fgets($file);
    fclose($file);
    $file = fopen("token.txt", "r");
    while(($line = fgets($file)) !== false){
        $arr = explode(",", $line);
        $cmp = hash_hmac("sha1", $arr[0], $secret);
        if(hash_equals($token, $cmp)){
            if(time() < (int)$arr[1]){
                return $line;
            } else {
                echo "<p>Timestemp expired! Please relogin!</p>";
                return false;
            }
        }
    }
    fclose($file);
    return false;
}

function getUsername($token) {
    $curl = curl_init();
    echo $token;
    curl_setopt($curl, CURLOPT_URL, "https://api.github.com/user");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: token $token"));
    curl_setopt($curl, CURLOPT_USERAGENT, "FSS");

    $rtn = curl_exec($curl);
    $rsp = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    if($rtn === false || $rsp >= 400){
        echo "<p>Error $rsp !</p>";
        return false;
    }
    $reg1 = explode("\"login\": \"", $rtn)[1];
    $reg2 = explode("\"", $rtn)[0];
    return $reg2;
}

// Displays table
function display($map) {
    $string = "<table>";
    foreach($map as $srg => $name) {
        $string = $string . "<tr><td>$srg</td><td><a href='srg.php?srg=$srg'>$name</a></td>\n\r";
    }
    return $string . "</table>";
}

function loadAll(){
    if(!isset($GLOBALS["methods"])){
        $GLOBALS["methods"] = readAndPack("methods.csv");
    }
    if(!isset($GLOBALS["params"])){
        $GLOBALS["params"] = readAndPack("params.csv");
    }
    if(!isset($GLOBALS["fields"])){
        $GLOBALS["fields"] = readAndPack("fields.csv");
    }
}

 // Checking for the srg value for not being mallissios
 function checkValidSrg($srg) {
    loadAll();
    if(isset($GLOBALS["methods"][$srg])) {
        return $GLOBALS["methods"];
    }
    if(isset($GLOBALS["params"][$srg])) {
        return $GLOBALS["params"];
    }
    if(isset($GLOBALS["fields"][$srg])) {
        return $GLOBALS["fields"];
    }
    return false;
}

 // Gets srgs from a name
 function getSrgFromName($name) {
    loadAll();
    $arr = array();
    foreach($GLOBALS["methods"] as $srg => $nm){
        if (strpos($nm, $name) !== false) {
            $arr[$srg] = $nm;
        }
    }
    foreach($GLOBALS["params"] as $srg => $nm){
        if (strpos($nm, $name) !== false) {
            $arr[$srg] = $nm;
        }
    }
    foreach($GLOBALS["fields"] as $srg => $nm){
        if (strpos($nm, $name) !== false) {
            $arr[$srg] = $nm;
        }
    }
    return $arr;
}

function load($pth) {
    $map = array();
    if(!file_exists($pth)){
        return $map;
    }
    $file = fopen($pth, "r");
    while(($line = fgets($file)) !== false){
        $arr = explode(",", $line);
        $map[$arr[0]] = (int)$arr[1];
    }
    asort($map);
    return $map;            
}

function write($pth, $map){
    $file = fopen($pth, "w");
    fwrite($map[0]);
    if(is_array($map[1])){
        for ($i=0; $i < count($map[1]); $i++) { 
            fwrite("," . $map[1][$i]);
        }
    } else {
        fwrite($map[1]);
    }
    fwrite("\n");
}

?>