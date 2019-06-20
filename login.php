<?php
if(!isset($_POST) || !isset($_POST["client_secret"])){
    header("Location: https://github.com/login/oauth/authorize?client_id=68f267e2a51c384bff92");
    die();
}
?>