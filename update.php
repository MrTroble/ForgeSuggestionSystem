<?php
include 'lib.php';

$GLOBALS["methods"] = readAndPack("methods.csv");
$GLOBALS["params"] = readAndPack("params.csv");
$GLOBALS["fields"] = readAndPack("fields.csv");
$file = fopen("cache.html", "w");
if($file){
    fwrite($file, "<link rel='stylesheet' type='text/css' href='main.css'>");
    fwrite($file, display($GLOBALS["methods"]));
    fwrite($file, display($GLOBALS["params"]));
    fwrite($file, display($GLOBALS["fields"]));
    fclose($file);
} else {
    echo "Error reading write!";
}
?>