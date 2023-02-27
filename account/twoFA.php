<?php
session_start();
$home = "../";
// Importing and setting up the template;
require("../templates/TemplateClass.php");
$template = new Template([
    // The path to the home page;
    "home" => $home,
    // The title of the page;
    "title" => "2FA",
    // The description of the page;
    "description" => "Two factor authentication page for scientific calculator"
]);

require_once("../class/AccountClass.php");
require_once("../class/Mailer.php");
$session_save = $_SESSION;
session_destroy();
session_start();
if(isset($_POST["twoFA"]) && $_POST["twoFA"] == $session_save["twoFA"]){
    $_SESSION = $session_save;
    header("Location: ../");
}else{
    $_SESSION = $session_save;
    $_SESSION["twoFA"] = unserialize($session_save["account"])->sendTwoFACode();
}

?>

<link rel="stylesheet" href="twoFA.css">

<form action="twoFA.php" method="post">
    <input type="text" name="twoFA" placeholder="Insert Code" required minlength="6" maxlength="6">
    <div>
        <input type="button" value="Resend Code">
        <input type="submit" value="Continue">
    </div>
</form>

<?php
// The content is printed;
$template->print();
?>
