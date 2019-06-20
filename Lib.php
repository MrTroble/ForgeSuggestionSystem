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

// Displays table
function display($map) {
    echo "<table>";
    foreach($map as $srg => $name) {
        echo "<tr><td>" . $srg . "</td><td><a href=\"?srg=" . $srg . "\">" . $name . "</a></td>";
    }
    echo "</table>";
}

 // Checking for the srg value for not being mallissios
 function checkValidSrg($srg) {
    if(isset($GLOBALS["methods"][$srg])) {
        return;
    }
    if(isset($GLOBALS["params"][$srg])) {
        return;
    }
    if(isset($GLOBALS["fields"][$srg])) {
        return;
    }
    echo("Invalide srg!");
    exit(403);
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