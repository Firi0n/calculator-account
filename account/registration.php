<?php
// Importing and setting up the template;
require("../templates/TemplateClass.php");
$template = new Template([
    // The path to the home page;
    "home" => "../",
    // The title of the page;
    "title" => "Registration",
    // The description of the page;
    "description" => "Registration page for scientific calculator"
]);
?>

<form action="login.php" method="post">
    <input type="text" name="username" placeholder="Username" required minlength="4">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required minlength="6">
    <input type="password" name="confirmPassword" placeholder="Confirm password" required minlength="6">
    <input type="submit" value="Register">
</form>

<?php
// The content is printed;
$template->print();
?>
