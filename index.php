<?php
    require("templates/TemplateClass.php");
    $template = new Template([
        "title" => "Home",
        "description" => "Scientific calculator"
    ], "templates/template.php");
?>



<?php $template->print(); ?>
