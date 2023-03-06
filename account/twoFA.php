<?php
// Importing and setting up the template;
session_start();
require("../templates/TemplateClass.php");
$template = new Template([
    // The title of the page;
    "title" => "2FA",
    // The description of the page;
    "description" => "Two factor authentication page for scientific calculator"
]);

require_once("../class/Account.php");
require_once("../class/Mailer.php");
$account = unserialize($_SESSION["account"]);
// If form is submitted, execute the login or registration;
if(isset($_POST["twoFA"]) && $_POST["twoFA"] == $_SESSION["twoFA"]){
    // Login or registration;
    $account->termLogOrReg();
}else{
    // Send the code;
    $_SESSION["twoFA"] = $account->twoFACode(new Mailer());
}

?>

<link rel="stylesheet" href="twoFA.css?<?= time() ?>">

<form action="twoFA.php" method="post">
    <input type="text" name="twoFA" placeholder="Insert Code" required minlength="6" maxlength="6">
    <div>
        <input type="button" value="Resend" onclick="location.reload(false);">
        <input type="submit" value="Continue">
    </div>
</form>

<?php
// The content is printed;
$template->print();
?>
