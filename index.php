<?php
// Importing and setting up the template;
session_start();
require("templates/TemplateClass.php");
$template = new Template([
    // The title of the page;
    "title" => "Home",
    // The description of the page;
    "description" => "Scientific calculator"
]);
// Require the calculator's function;
require("calculator.php");
// If the user has sent the form, the result is calculated;
if(isset($_POST["equation"])){
    // The result is calculated;
    $result = calcFromString($_POST["equation"]);
    // The result is converted to the selected format;
    switch($_POST["result"]){
        case "binary":
            $result = decbin($result);
            break;
        case "octal":
            $result = decoct($result);
            break;
        case "hexadecimal":
            $result = dechex($result);
            break;
    }
}else{
    // If the user has not sent the form, the result is empty;
    $result = "";
}

?>

<form action="index.php" method="post">
    <textarea name="equation" placeholder="Enter your equation here" required><?= $result ?></textarea>
    <select name="result">
        <option value="decimal">Decimal</option>
        <option value="binary">Binary</option>
        <option value="octal">Octal</option>
        <option value="hexadecimal">Hexadecimal</option>
    </select>
    <?=
    // If the user is logged in, the button is displayed, otherwise the login and registration links are displayed;
    isset($_SESSION["id"]) ? "<input type='submit' value='Calculate'>" : 
    "<label>
        <a href='account/login.php'>Login</a> or <a href='account/registration.php'>Registration</a> for calculate.
    </label>" 
    ?>
</form>

<?php
// The content is printed;
$template->print();
?>
