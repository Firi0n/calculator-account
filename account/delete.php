<?php
session_start();
    require("../class/AccountClass.php");
    require_once("../class/Mailer.php");
    $account = unserialize($_SESSION["account"]);
    $account->deleteAccount();
?>
