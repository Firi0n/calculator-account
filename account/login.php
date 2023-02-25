<?php
// Importing and setting up the template;
require("../templates/TemplateClass.php");
$template = new Template([
    // The path to the home page;
    "home" => "../",
    // The title of the page;
    "title" => "Login",
    // The description of the page;
    "description" => "Login page for scientific calculator"
]);
?>

<form action="login.php" method="post">
    <input type="text" name="username" placeholder="Username" required minlength="4">
    <input type="password" name="password" placeholder="Password" required minlength="6">
    <input type="submit" value="Login">
</form>

<?php
// The content is printed;
$template->print();
?>
