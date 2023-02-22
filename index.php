<?php
require("templates/TemplateClass.php");
$template = new Template([
    "home" => "./",
    "title" => "Home",
    "description" => "Scientific calculator"
]);

?>

<form action="index.php" method="post">
    <input type="text" name="equation" placeholder="Enter your equation here">
    <select name="result">
        <option value="decimal">Decimal</option>
        <option value="binary">Binary</option>
        <option value="octal">Octal</option>
        <option value="hexadecimal">Hexadecimal</option>
    </select>
</form>

<?php $template->print(); ?>
