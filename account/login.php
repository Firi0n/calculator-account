<?php
// Importing and setting up the template;
require("../templates/TemplateClass.php");
$template = new Template([
    // The title of the page;
    "title" => "Login",
    // The description of the page;
    "description" => "Login page for scientific calculator"
]);
if(isset($_POST["username"]) && isset($_POST["password"])) {
    // Create account class;
    require_once("../class/AccountClass.php");
    require_once("../class/Mailer.php");
    $account = new Account(new Mailer($home));
    // The user is logged in;
    $result = $account->login($_POST["username"], $_POST["password"]);
    // If the user is logged in, the user is redirected to the home page;
    if($result == 0) {
        header(($account->getData()["twoFA"] ? "Location: twoFA.php" : "Location: ../"));
    }
}
?>

<form action="login.php" method="post">
    <input type="text" name="username" placeholder="Username" required minlength="4">
    <!-- If the user does not exist, the error message is displayed; -->
    <?= (isset($result) && $result == 2) ? "<label class='error'>Username doesn't exists</label>" : "" ?>
    <input type="password" name="password" placeholder="Password" required minlength="6">
    <!-- If the password is incorrect, the error message is displayed; -->
    <?= (isset($result) && $result == 1) ? "<label class='error'>Wrong password or too many login attempts</label>" : "" ?>
    <input type="submit" value="Login">
</form>

<?php
// The content is printed;
$template->print();
?>
