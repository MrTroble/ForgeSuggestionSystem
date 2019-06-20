<?php
var_dump($_POST);
if(!isset($_POST) || !isset($_POST["code"])){
    header("Location: https://github.com/login/oauth/authorize?client_id=68f267e2a51c384bff92");
    die();
}
?>