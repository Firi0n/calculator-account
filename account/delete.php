<?php
session_start();
    require("../class/Account.php");
    $account = unserialize($_SESSION["account"]);
    $account->deleteAccount();
?>
