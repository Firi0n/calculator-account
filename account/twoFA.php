<?php
// Importing and setting up the template;
require("../templates/TemplateClass.php");
$template = new Template([
    // The path to the home page;
    "home" => "../",
    // The title of the page;
    "title" => "Two factor authentication",
    // The description of the page;
    "description" => "Two factor authentication page for scientific calculator"
]);
?>

<form action="login.php" method="post">
    <input type="text" name="twoFA" placeholder="Insert Code" required minlength="6" maxlength="6">
    <div>
        <input type="button" value="Resend Code">
        <input type="submit" value="Continue">
    </div>
</form>

<style>
    input{
        width: 37vw;
    }
    div > input {
        width: 18vw;
    }
</style>

<?php
// The content is printed;
$template->print();
?>
