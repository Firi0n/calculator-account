<?php
// Importing and setting up the template;
require("../templates/TemplateClass.php");
$template = new Template([
    // The path to the home page;
    "home" => "../",
    // The title of the page;
    "title" => "2FA",
    // The description of the page;
    "description" => "Two factor authentication page for scientific calculator"
]);
?>

<link rel="stylesheet" href="twoFA.css">

<form action="login.php" method="post">
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
