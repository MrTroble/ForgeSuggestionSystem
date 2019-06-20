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
    $file = fopen("token.txt", "r");
    echo "Check";
    while(($line = fgets($file)) !== false){
        $arr = explode(",", $line);
        echo $line;
        if($token === $arr[0]){
            if(time() > (int)$arr[1]){
                return true;
            } else {
                echo "Timestemp expired!";
                return false;
            }
        }
    }
    return false;
}

// Displays table
function display($map) {
    $string = "<table>";
    foreach($map as $srg => $name) {
        $string = $string . "<tr><td>" . $srg . "</td><td><a href=\"?srg=" . $srg . "\" target=''>" . $name . "</a></td>\n\r";
    }
    return $string . "</table>";
}

function loadAll(){
$GLOBALS["methods"] = readAndPack("methods.csv");
$GLOBALS["params"] = readAndPack("params.csv");
$GLOBALS["fields"] = readAndPack("fields.csv");
}

 // Checking for the srg value for not being mallissios
 function checkValidSrg($srg) {
    loadAll();
    if(isset($GLOBALS["methods"][$srg])) {
        return  $GLOBALS["methods"];
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

?>