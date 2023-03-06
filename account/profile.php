<?php
// Importing and setting up the template;
session_start();
require("../templates/TemplateClass.php");
$template = new Template([
    // The title of the page;
    "title" => "Profile",
    // The description of the page;
    "description" => "Profile page for scientific calculator"
]);
require("../class/Account.php");
require_once("../class/Mailer.php");
$account = unserialize($_SESSION["account"]);
// If form is submitted, the data is changed;
if(isset($_POST["username"]) && isset($_POST["email"])) {
    // The user is logged in;
    $result = $account->changeData($_POST["username"], $_POST["email"], ($_POST["password"] != "" ? $_POST["password"] : $account->getData()["password"]), isset($_POST["twoFA"]));
}
?>

<form id="profile" action="profile.php" method="post">
    <?= isset($result) && $result >= 3 ? "<label class='error'>Generic Error</label>" : "" ?>
    <label>Username:</label>
    <input type="text" name="username" value="<?= $account->getData()["username"]; ?>" required minlength="4">
    <!-- If the user does exist, the error message is displayed; -->
    <?= isset($result) && $result == 1 ? "<label class='error'>Username already exists</label>" : "" ?>
    <label>Email:</label>
    <input type="email" name="email" value="<?= $account->getData()["email"]; ?>" required>
    <!-- If the email is already in use, the error message is displayed; -->
    <?= isset($result) && $result == 2 ? "<label class='error'>Email already in use</label>" : "" ?>
    <input id="password" type="password" name="password" placeholder="Change Password" minlength="6">
    <input id="confirmPassword" type="password" name="confirmPassword" placeholder="Confirm password" minlength="6">
    <!-- If the passwords dom't match, the error message is displayed; -->
    <label class="error" id="error" style="display : none">Passwords don't match</label>
    <div>
        <label>2fa:</label>
        <input type="checkbox" name="twoFA" <?= $account->getData()["twoFA"] != 0 ? "checked" : ""; ?> >
    </div>
    <input type="submit" value="Change">
    <input type="button" value="Delete" onclick="window.location.href='delete.php'">
</form>

<script>
    //Selecting elements;
    let password = document.getElementById("password");
    let confirmPassword = document.getElementById("confirmPassword");
    let form = document.getElementById("profile");
    let error = document.getElementById("error");
    //Adding event listener;
    form.addEventListener("submit", function(event) {
        //If passwords do not match, the event is prevented and the alert is displayed;
        if(password.value !== confirmPassword.value) {
            event.preventDefault();
            error.style.display = "block";
        }
    });
</script>

<?php
// The content is printed;
$template->print();
?>
