<?php
// Importing and setting up the template;
require("../templates/TemplateClass.php");
$home = "../";
$template = new Template([
    // The path to the home page;
    "home" => $home,
    // The title of the page;
    "title" => "Login",
    // The description of the page;
    "description" => "Login page for scientific calculator"
]);
if(isset($_POST["username"]) && isset($_POST["password"])) {
    // Create account class;
    require_once("../class/AccountClass.php");
    require_once("../class/Mailer.php");
    $account = new Account($home, new Mailer($home));
    // The user is logged in;
    $return = $account->login($_POST["username"], $_POST["password"]);
    // If the user is logged in, the user is redirected to the home page;
    if($return["username"] && $return["password"]) {
        header("Location: ".$home);
    }
}
?>

<form action="login.php" method="post">
    <input type="text" name="username" placeholder="Username" required minlength="4">
    <!-- If the user does not exist, the error message is displayed; -->
    <?= isset($return) && !$return["username"] ? "<label class='error'>Username does not exist</label>" : "" ?>
    <input type="password" name="password" placeholder="Password" required minlength="6">
    <!-- If the password is incorrect, the error message is displayed; -->
    <?= isset($return) && !$return["password"] ? "<label class='error'>Password is incorrect</label>" : "" ?>
    <input type="submit" value="Login">
</form>

<?php
// The content is printed;
$template->print();
?>
