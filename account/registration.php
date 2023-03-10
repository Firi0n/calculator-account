<?php
// Importing and setting up the template;
require("../templates/TemplateClass.php");
$template = new Template([
    // The title of the page;
    "title" => "Registration",
    // The description of the page;
    "description" => "Registration page for scientific calculator"
]);
if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])) {
    // Create account class;
    require("../class/Account.php");
    $account = new Account();
    // The user is logged in;
    $result = $account->registration($_POST["username"], $_POST["email"], $_POST["password"]);
}
?>

<form id="registration" action="registration.php" method="post">
    <?= isset($result) && $result == 3 ? "<label class='error'>Generic Error</label>" : "" ?>
    <input type="text" name="username" placeholder="Username" required minlength="4">
    <!-- If the user does exist, the error message is displayed; -->
    <?= isset($result) && $result == 1 ? "<label class='error'>Username already exists</label>" : "" ?>
    <input type="email" name="email" placeholder="Email" required>
    <!-- If the email is already in use, the error message is displayed; -->
    <?= isset($result) && $result == 2 ? "<label class='error'>Email already in use</label>" : "" ?>
    <input id="password" type="password" name="password" placeholder="Password" required minlength="6">
    <input id="confirmPassword" type="password" name="confirmPassword" placeholder="Confirm password" required minlength="6">
    <!-- If the passwords dom't match, the error message is displayed; -->
    <label class="error" id="error" style="display : none">Passwords don't match</label>
    <input type="submit" value="Register">
</form>

<script>
    //Selecting elements;
    let password = document.getElementById("password");
    let confirmPassword = document.getElementById("confirmPassword");
    let form = document.getElementById("registration");
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
